<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolver implements ResolverInterface
{
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        $type = $parameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        if (
            ! class_exists($type->getName())
            && ! interface_exists($type->getName())
        ) {
            return null;
        }

        return $this->handleClass(new ReflectionClass($type->getName()));
    }

    private function handleClass(ReflectionClass $reflectionClass): ?AutoWiringContainer
    {
        if (
            $reflectionClass->isInterface()
            && $reflectionClass->getName() === ContainerInterface::class
        ) {
            return new AutoWiringContainer();
        }

        if ($reflectionClass->getName() === AbstractPluginManager::class) {
            return null;
        }

        $interfaceNames = $reflectionClass->getInterfaceNames();
        if (in_array(ContainerInterface::class, $interfaceNames, true)) {
            return new AutoWiringContainer();
        }

        return null;
    }
}
