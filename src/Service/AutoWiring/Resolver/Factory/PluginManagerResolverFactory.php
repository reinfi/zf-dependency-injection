<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory
 */
class PluginManagerResolverFactory
{
    public function __invoke(ContainerInterface $container): PluginManagerResolver
    {
        return new PluginManagerResolver($container);
    }
}
