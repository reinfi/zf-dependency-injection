<?php

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Traversable;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class AutoWiringFactory implements FactoryInterface
{
    /**
     * Options to pass to the constructor (when used in v2), if any.
     *
     * @param null|array
     */
    private $creationOptions;

    /**
     * @param null|array|Traversable $creationOptions
     * @throws InvalidServiceException if $creationOptions cannot be coerced to
     *     an array.
     */
    public function __construct($creationOptions = null)
    {
        if (null === $creationOptions) {
            return;
        }

        $this->creationOptions = $creationOptions;
    }

    /**
     * Create an instance of the requested class name.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AutoWiringService $autoWiringService */
        if ($container instanceof AbstractPluginManager) {
            $autoWiringService = $container->getServiceLocator()->get(AutoWiringService::class);
        } else {
            $autoWiringService = $container->get(AutoWiringService::class);
        }

        $injections = $autoWiringService->resolveConstructorInjection(
            $container,
            $requestedName
        );

        if ($injections === false) {
            return new $requestedName;
        }

        $reflClass = new \ReflectionClass($requestedName);

        $instance = $reflClass->newInstanceArgs($injections);

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
        if (is_string($requestedName) && class_exists($requestedName)) {
            return $this($serviceLocator, $requestedName, $this->creationOptions);
        }

        if (class_exists($canonicalName)) {
            return $this($serviceLocator, $canonicalName, $this->creationOptions);
        }

        throw new InvalidServiceException(sprintf(
          '%s requires that the requested name is provided on invocation; please update your tests or consuming container',
          __CLASS__
      ));
    }
}