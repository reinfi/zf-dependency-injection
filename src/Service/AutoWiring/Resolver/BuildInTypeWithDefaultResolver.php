<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Injection\Value;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class BuildInTypeWithDefaultResolver implements ResolverInterface
{
    public function resolve(ReflectionParameter $reflectionParameter): ?InjectionInterface
    {
        $type = $reflectionParameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        if (! $type->isBuiltin()) {
            return null;
        }

        if (! $reflectionParameter->isDefaultValueAvailable()) {
            return null;
        }

        return new Value($reflectionParameter->getDefaultValue());
    }
}
