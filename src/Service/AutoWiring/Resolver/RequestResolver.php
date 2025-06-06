<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Laminas\Stdlib\RequestInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class RequestResolver implements ResolverInterface
{
    public function resolve(ReflectionParameter $reflectionParameter): ?InjectionInterface
    {
        $type = $reflectionParameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        if (
            ! class_exists($type->getName())
            && ! interface_exists($type->getName())
        ) {
            return null;
        }

        $reflectionClass = new ReflectionClass($type->getName());
        $interfaceNames = $reflectionClass->getInterfaceNames();

        if (
            $reflectionClass->getName() !== RequestInterface::class
            && ! in_array(RequestInterface::class, $interfaceNames, true)
        ) {
            return null;
        }

        return new AutoWiring('Request');
    }
}
