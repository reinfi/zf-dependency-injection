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
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType) {
            return null;
        }

        if ($this->container->has($type->getName())) {
            return new AutoWiring($type->getName());
        }

        return null;
    }
}
