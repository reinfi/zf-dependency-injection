<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;

/**
 * @package Reinfi\DependencyInjection\Test\Test\Unit\Service
 */
class InjectionServiceTest extends TestCase
{
    use CacheKeyTrait;

    public function testItResolvesConstructorArguments(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->createMock(ExtractorInterface::class);

        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        $extractor->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service1::class)
            ->willReturn([]);
        $extractor->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service1::class)
            ->willReturn([$injection]);

        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('set')
            ->with($cacheKey, $this->isArray())
            ->willReturn(true);

        $service = new InjectionService($extractor, $cache);

        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItResolvesPropertyInjections(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->createMock(ExtractorInterface::class);

        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        $extractor->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service1::class)
            ->willReturn([]);
        $extractor->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service1::class)
            ->willReturn([$injection]);

        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('set')
            ->with($cacheKey, $this->isArray())
            ->willReturn(true);

        $service = new InjectionService($extractor, $cache);

        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItUsesCacheItemWhenFound(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->createMock(ExtractorInterface::class);

        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);
        $cache->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn([$injection]);

        $service = new InjectionService($extractor, $cache);

        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItUsesExtractorWhenCacheItemIsNotAnArray(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->createMock(ExtractorInterface::class);

        $injection = $this->createMock(InjectionInterface::class);
        $injection->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(ContainerInterface::class))
            ->willReturn(new Service2());

        $extractor->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service1::class)
            ->willReturn([]);
        $extractor->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service1::class)
            ->willReturn([$injection]);

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
            ->with($cacheKey, $this->isArray())
            ->willReturn(true);

        $service = new InjectionService($extractor, $cache);

        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service1::class);

        self::assertCount(1, $injections);
    }

    public function testItReturnsFalseWhenNoInjectionsAvaible(): void
    {
        $cacheKey = $this->buildCacheKey(Service2::class);
        $extractor = $this->createMock(ExtractorInterface::class);
        $extractor->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service2::class)
            ->willReturn([]);
        $extractor->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service2::class)
            ->willReturn([]);

        $cache = $this->createMock(CacheService::class);
        $cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(false);
        $cache->expects($this->once())
            ->method('set')
            ->with($cacheKey, $this->isArray())
            ->willReturn(true);

        $service = new InjectionService($extractor, $cache);

        $container = $this->createMock(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection($container, Service2::class);

        self::assertFalse($injections);
    }
}
