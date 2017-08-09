<?php

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Traversable;
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
     * Options to pass to the constructor (when used in v2), if any.
     *
     * @param null|array
     */
    private $creationOptions;

    /**
     * @var array
     */
    private static $injectionConfig;

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
        $this->loadConfig($container);

        if ($this->hasInjections($requestedName) === false) {

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
                return (null === $options) ? new $requestedName : new $requestedName($options);
            }
        } else {
            $injections = $this->buildDependencies($container, $requestedName);
        }

        return new $requestedName($injections);
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

    /**
     * @param ContainerInterface $container
     */
    private function loadConfig(ContainerInterface $container)
    {
        if (is_array(self::$injectionConfig)) {
            return;
        }

        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        $serviceManagerConfig = $container->get('config')['service_manager'];

        self::$injectionConfig = $serviceManagerConfig['injections'] ?? [];
    }

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     *
     * @return array
     */
    private function buildDependencies(
        ContainerInterface $container,
        string $requestedName
    ): array {
        $injections = self::$injectionConfig[$requestedName];

        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return array_map([$container, 'get'], $injections);
    }

    /**
     * @param string $requestedName
     *
     * @return bool
     */
    private function hasInjections(string $requestedName): bool
    {
        return isset(self::$injectionConfig[$requestedName]);
    }
}