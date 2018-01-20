<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory
 */
class ContainerResolverFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ContainerResolver
     */
    public function __invoke(ContainerInterface $container): ContainerResolver
    {
        return new ContainerResolver($container);
    }
}
