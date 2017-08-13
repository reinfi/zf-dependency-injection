<?php

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class InjectionFactory implements FactoryInterface
{
    /**
     * Create an instance of the requested class name.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     *
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        /** @var InjectionService $injectionService */
        if ($container instanceof AbstractPluginManager) {
            $injectionService = $container->getServiceLocator()->get(InjectionService::class);
        } else {
            $injectionService = $container->get(InjectionService::class);
        }

        $injections = $injectionService->resolveConstructorInjection(
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
            return $this($serviceLocator, $requestedName);
        }

        if (class_exists($canonicalName)) {
            return $this($serviceLocator, $canonicalName);
        }

        throw new InvalidServiceException(sprintf(
          '%s requires that the requested name is provided on invocation; please update your tests or consuming container',
          __CLASS__
      ));
    }
}