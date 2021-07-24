<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Laminas\ServiceManager\AbstractPluginManager;

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
        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType) {
            return null;
        }

        if (
            !class_exists($type->getName())
            && !interface_exists($type->getName())
        ) {
            return null;
        }

        return $this->handleClass(new ReflectionClass($type->getName()));
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return AutoWiringContainer|null
     */
    private function handleClass(
        ReflectionClass $reflectionClass
    ): ?AutoWiringContainer {
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
        if (in_array(ContainerInterface::class, $interfaceNames)) {
            return new AutoWiringContainer();
        }

        return null;
    }
}
