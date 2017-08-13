<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\PluginManagerResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver\Factory
 */
class PluginManagerResolverFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsContainerResolver()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $factory = new PluginManagerResolverFactory();

        $this->assertInstanceOf(
            PluginManagerResolver::class,
            $factory($container->reveal())
        );
    }
}