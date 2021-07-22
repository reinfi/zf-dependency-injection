<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Laminas\Stdlib\ResponseInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class ResponseResolver implements ResolverInterface
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
            !class_exists($type->getName(), false)
            && !interface_exists($type->getName(), false)
        ) {
            return null;
        }

        $reflectionClass = new ReflectionClass($type->getName());
        $interfaceNames = $reflectionClass->getInterfaceNames();

        if (
            $reflectionClass->getName() !== ResponseInterface::class
            && !in_array(ResponseInterface::class, $interfaceNames)
        ) {
            return null;
        }

        return new AutoWiring('Response');
    }
}
