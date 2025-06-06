<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\PluginManagerResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory
 */
final class PluginManagerResolverFactoryTest extends TestCase
{
    public function testItReturnsPluginManagerResolver(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $pluginManagerResolverFactory = new PluginManagerResolverFactory();

        self::assertInstanceOf(PluginManagerResolver::class, $pluginManagerResolverFactory($container));
    }
}
