<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter)
    {
        $reflClass = $parameter->getClass();

        if ($reflClass->isInterface()) {
            if ($reflClass->getName() === ContainerInterface::class) {
                return new AutoWiringContainer();
            }
        }

        $interfaceNames = $reflClass->getInterfaceNames();
        if (in_array(ContainerInterface::class, $interfaceNames)) {
            return new AutoWiringContainer();
        }

        return null;
    }
}