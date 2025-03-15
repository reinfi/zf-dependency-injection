<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring
 *
 * @group unit
 */
class LazyResolverServiceTest extends TestCase
{
    public function testItResolvesResolverServiceLazy(): void
    {
        $resolverService = $this->createMock(ResolverServiceInterface::class);
        $resolverService->expects($this->once())
            ->method('resolve')
            ->with('test', null)
            ->willReturn([]);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ResolverService::class)
            ->willReturn($resolverService);

        $service = new LazyResolverService($container);

        $service->resolve('test');
    }

    public function testItResolvesResolverServiceLazyWithOptions(): void
    {
        $options = [
            'foo' => 'bar',
        ];

        $resolverService = $this->createMock(ResolverServiceInterface::class);
        $resolverService->expects($this->once())
            ->method('resolve')
            ->with('test', $options)
            ->willReturn([]);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ResolverService::class)
            ->willReturn($resolverService);

        $service = new LazyResolverService($container);

        $service->resolve('test', $options);
    }

    public function testItResolvesResolverServiceOnlyOnce(): void
    {
        $resolverService = $this->createMock(ResolverServiceInterface::class);
        $resolverService->expects($this->exactly(2))
            ->method('resolve')
            ->willReturnCallback(function (string $class, ?array $options) {
                static $calls = 0;
                $calls++;
                if ($calls === 1) {
                    $this->assertEquals('test', $class);
                    $this->assertNull($options);
                } else {
                    $this->assertEquals('test2', $class);
                    $this->assertNull($options);
                }
                return [];
            });

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ResolverService::class)
            ->willReturn($resolverService);

        $service = new LazyResolverService($container);

        $service->resolve('test');
        $service->resolve('test2');
    }
}
