<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring
 */
#[Group('unit')]
final class LazyResolverServiceTest extends TestCase
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

        $lazyResolverService = new LazyResolverService($container);

        $lazyResolverService->resolve('test');
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

        $lazyResolverService = new LazyResolverService($container);

        $lazyResolverService->resolve('test', $options);
    }

    public function testItResolvesResolverServiceOnlyOnce(): void
    {
        $resolverService = $this->createMock(ResolverServiceInterface::class);
        $resolverService->expects($this->exactly(2))
            ->method('resolve')
            ->willReturnCallback(function (string $class, ?array $options): array {
                static $calls = 0;
                ++$calls;
                if ($calls === 1) {
                    self::assertSame('test', $class);
                    self::assertNull($options);
                } else {
                    self::assertSame('test2', $class);
                    self::assertNull($options);
                }

                return [];
            });

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ResolverService::class)
            ->willReturn($resolverService);

        $lazyResolverService = new LazyResolverService($container);

        $lazyResolverService->resolve('test');
        $lazyResolverService->resolve('test2');
    }
}
