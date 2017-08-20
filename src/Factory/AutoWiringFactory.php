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
}