<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\ContainerResolverFactory;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver\Factory
 */
class ContainerResolverFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsContainerResolver()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $factory = new ContainerResolverFactory();

        $this->assertInstanceOf(
            ContainerResolver::class,
            $factory($container->reveal())
        );
    }
}