<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\ContainerResolverFactory;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory
 */
class ContainerResolverFactoryTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
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
