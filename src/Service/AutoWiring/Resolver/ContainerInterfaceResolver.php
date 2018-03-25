<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        $reflClass = $parameter->getClass();
        if (
            $reflClass->isInterface()
            && $reflClass->getName() === ContainerInterface::class
        ) {
            return new AutoWiringContainer();
        }

        if ($reflClass->getName() === AbstractPluginManager::class) {
            return null;
        }

        $interfaceNames = $reflClass->getInterfaceNames();
        if (in_array(ContainerInterface::class, $interfaceNames)) {
            return new AutoWiringContainer();
        }

        return null;
    }
}
