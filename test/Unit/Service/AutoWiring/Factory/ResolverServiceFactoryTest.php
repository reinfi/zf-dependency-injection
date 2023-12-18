<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\AutoWiring\Factory\ResolverServiceFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\BuildInTypeWithDefaultResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResponseResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Test\Service\Resolver\TestResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Factory
 */
class ResolverServiceFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testItCreatesResolverServiceWithDefaultResolvers(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn([]);

        $container->get(ContainerResolver::class)->shouldBeCalled();
        $container->get(PluginManagerResolver::class)->shouldBeCalled();
        $container->get(ContainerInterfaceResolver::class)->shouldBeCalled();
        $container->get(RequestResolver::class)->shouldBeCalled();
        $container->get(ResponseResolver::class)->shouldBeCalled();
        $container->get(BuildInTypeWithDefaultResolver::class)->shouldBeCalled();

        $factory = new ResolverServiceFactory();

        self::assertInstanceOf(
            ResolverService::class,
            $factory($container->reveal()),
            'factory should return instance of ' . ResolverService::class
        );
    }

    public function testItCreatesResolverServiceWithAdditionalResolvers(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn([
                'autowire_resolver' => [TestResolver::class],
            ]);

        $container->get(ContainerResolver::class)->shouldBeCalled();
        $container->get(PluginManagerResolver::class)->shouldBeCalled();
        $container->get(ContainerInterfaceResolver::class)->shouldBeCalled();
        $container->get(RequestResolver::class)->shouldBeCalled();
        $container->get(ResponseResolver::class)->shouldBeCalled();
        $container->get(BuildInTypeWithDefaultResolver::class)->shouldBeCalled();
        $container->get(TestResolver::class)->shouldBeCalled();

        $factory = new ResolverServiceFactory();

        self::assertInstanceOf(
            ResolverService::class,
            $factory($container->reveal()),
            'factory should return instance of ' . ResolverService::class
        );
    }
}
