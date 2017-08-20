<?php

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class AutoWiringFactory extends AbstractFactory
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
        $autoWiringService = $this->getAutoWiringService($container);

        $injections = $autoWiringService->resolveConstructorInjection(
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
     * @return AutoWiringService
     */
    private function getAutoWiringService(ContainerInterface $container): AutoWiringService
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return $container->get(AutoWiringService::class);
    }
}