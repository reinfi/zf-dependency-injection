<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
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

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return InjectionInterface|null
     */
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        if ($this->container->has($parameter->getClass()->getName())) {
            return new AutoWiring($parameter->getClass()->getName());
        }

        return null;
    }
}
