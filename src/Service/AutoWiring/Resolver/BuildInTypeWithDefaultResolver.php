<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Injection\Value;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class BuildInTypeWithDefaultResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        if (
            !$parameter->hasType()
            || $parameter->getType() === null
            || !$parameter->getType()->isBuiltin()
        ) {
            return null;
        }

        if (!$parameter->isDefaultValueAvailable()) {
            return null;
        }

        return new Value($parameter->getDefaultValue());
    }
}
