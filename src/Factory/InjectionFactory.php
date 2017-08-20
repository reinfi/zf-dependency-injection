<?php

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class InjectionFactory extends AbstractFactory
{
    /**
     * Create an instance of the requested class name.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     *
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $requestedName)
    {
        $injectionService = $this->getInjectionService($container);

        $injections = $injectionService->resolveConstructorInjection(
            $container,
            $requestedName
        );

        if ($injections === false) {
            return new $requestedName;
        }

        return $this->buildInstance($requestedName, $injections);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return InjectionService
     */
    private function getInjectionService(ContainerInterface $container): InjectionService
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return $container->get(InjectionService::class);
    }
}