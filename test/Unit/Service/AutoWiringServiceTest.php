<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;

/**
 * @package Reinfi\DependencyInjection\Test\Test\Unit\Service
 */
class AutoWiringServiceTest extends TestCase
{
    use CacheKeyTrait;

    public function testItResolvesConstructorInjection(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);

        // Create and configure resolver mock
        $resolver = $this->createMock(ResolverService::class);
        $options = [
            'foo' => 'bar',
        ];

        // Create injection mocks
        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        $optionsInjection = $this->createMock(InjectionInterface::class);
        $optionsInjection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn('bar');

        // Configure resolver expectations
        $resolver->expects($this->once())
            ->method('resolve')
            ->with(Service1::class, $options)
            ->willReturn([$injection, $optionsInjection]);

        // Create cache mock - should not be used when options are provided
        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->never())->method('has');
        $cache->expects($this->never())->method('set');

        $service = new AutoWiringService($resolver, $cache);

        // Create container mock
        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class, $options);

        self::assertCount(2, $injections);
    }

    public function testItResolvesConstructorArguments(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);

        // Create and configure resolver mock
        $resolver = $this->createMock(ResolverService::class);

        // Create injection mock
        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        // Configure resolver expectations
        $resolver->expects($this->once())
            ->method('resolve')
            ->with(Service1::class, null)
            ->willReturn([$injection]);

        // Create and configure cache mock
        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);

        $cache->expects($this->once())
            ->method('set')
            ->with($cacheKey, $this->isType('array'))
            ->willReturn(true);

        $service = new AutoWiringService($resolver, $cache);

        // Create container mock
        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItUsesCacheItemWhenFound(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);

        // Create resolver mock (no expectations needed)
        $resolver = $this->createMock(ResolverService::class);

        // Create injection mock
        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        // Create and configure cache mock
        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);

        $cache->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn([$injection]);

        $service = new AutoWiringService($resolver, $cache);

        // Create container mock
        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItDoesNotCacheOptions(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);

        // Create and configure resolver mock
        $resolver = $this->createMock(ResolverService::class);
        $options = [
            'foo' => 'bar',
        ];

        // Create injection mocks
        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        $optionsInjection = $this->createMock(InjectionInterface::class);
        $optionsInjection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn('bar');

        // Configure resolver expectations
        $resolver->expects($this->once())
            ->method('resolve')
            ->with(Service1::class, $options)
            ->willReturn([$injection, $optionsInjection]);

        // Create cache mock with no expectations (should not be called)
        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->never())->method('set');
        $cache->expects($this->never())->method('has');
        $cache->expects($this->never())->method('get');

        $service = new AutoWiringService($resolver, $cache);

        // Create container mock
        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class, $options);

        self::assertCount(2, $injections);
    }

    public function testItUsesResolverWhenCacheItemIsNotAnArray(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);

        // Create and configure resolver mock
        $resolver = $this->createMock(ResolverService::class);

        // Create injection mock
        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        // Configure resolver expectations
        $resolver->expects($this->once())
            ->method('resolve')
            ->with(Service1::class, null)
            ->willReturn([$injection]);

        // Create and configure cache mock
        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);

        $cache->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn(null);

        $cache->expects($this->once())
            ->method('set')
            ->with($cacheKey, $this->isType('array'))
            ->willReturn(true);

        $service = new AutoWiringService($resolver, $cache);

        // Create container mock
        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItReturnsFalseWhenNoInjectionsAvailable(): void
    {
        $cacheKey = $this->buildCacheKey(Service2::class);

        // Create and configure resolver mock
        $resolver = $this->createMock(ResolverService::class);
        $resolver->expects($this->once())
            ->method('resolve')
            ->with(Service2::class, null)
            ->willReturn([]);

        // Create and configure cache mock
        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);

        $cache->expects($this->once())
            ->method('set')
            ->with($cacheKey, $this->isType('array'))
            ->willReturn(true);

        $service = new AutoWiringService($resolver, $cache);

        // Create container mock
        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service2::class);

        self::assertNull($injections, 'Return value should be null if service has no injections');
    }
}
