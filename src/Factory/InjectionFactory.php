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
}