<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class ContainerResolver implements ResolverInterface
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function resolve(ReflectionParameter $reflectionParameter): ?InjectionInterface
    {
        $type = $reflectionParameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        if ($this->container->has($type->getName())) {
            return new AutoWiring($type->getName());
        }

        return null;
    }
}
