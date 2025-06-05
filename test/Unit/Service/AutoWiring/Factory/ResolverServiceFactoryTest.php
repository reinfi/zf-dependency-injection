<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Factory;

use PHPUnit\Framework\TestCase;
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
final class ResolverServiceFactoryTest extends TestCase
{
    public function testItCreatesResolverServiceWithDefaultResolvers(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')
            ->willReturnCallback(function (string $service): mixed {
                if ($service === ModuleConfig::class) {
                    return [];
                }

                return match ($service) {
                    ContainerResolver::class => $this->createMock(ContainerResolver::class),
                    PluginManagerResolver::class => $this->createMock(PluginManagerResolver::class),
                    ContainerInterfaceResolver::class => $this->createMock(ContainerInterfaceResolver::class),
                    RequestResolver::class => $this->createMock(RequestResolver::class),
                    ResponseResolver::class => $this->createMock(ResponseResolver::class),
                    BuildInTypeWithDefaultResolver::class => $this->createMock(BuildInTypeWithDefaultResolver::class),
                    default => null,
                };
            });

        $resolverServiceFactory = new ResolverServiceFactory();

        self::assertInstanceOf(
            ResolverService::class,
            $resolverServiceFactory($container),
            'factory should return instance of ' . ResolverService::class
        );
    }

    public function testItCreatesResolverServiceWithAdditionalResolvers(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')
            ->willReturnCallback(function (string $service): mixed {
                if ($service === ModuleConfig::class) {
                    return [
                        'autowire_resolver' => [TestResolver::class],
                    ];
                }

                return match ($service) {
                    ContainerResolver::class => $this->createMock(ContainerResolver::class),
                    PluginManagerResolver::class => $this->createMock(PluginManagerResolver::class),
                    ContainerInterfaceResolver::class => $this->createMock(ContainerInterfaceResolver::class),
                    RequestResolver::class => $this->createMock(RequestResolver::class),
                    ResponseResolver::class => $this->createMock(ResponseResolver::class),
                    BuildInTypeWithDefaultResolver::class => $this->createMock(BuildInTypeWithDefaultResolver::class),
                    TestResolver::class => $this->createMock(TestResolver::class),
                    default => null,
                };
            });

        $resolverServiceFactory = new ResolverServiceFactory();

        self::assertInstanceOf(
            ResolverService::class,
            $resolverServiceFactory($container),
            'factory should return instance of ' . ResolverService::class
        );
    }
}
