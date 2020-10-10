<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
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
    use ProphecyTrait;
    use CacheKeyTrait;

    /**
     * @test
     */
    public function itResolvesConstructorArguments()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->prophesize(ExtractorInterface::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
                ->willReturn(new Service2())
                ->shouldBeCalled()
        );

        $extractor->getPropertiesInjections(Service1::class)
            ->willReturn([]);
        $extractor->getConstructorInjections(Service1::class)
            ->willReturn([
                $injection->reveal(),
             ]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(false);
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new InjectionService(
            $extractor->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        $this->assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itResolvesPropertyInjections()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->prophesize(ExtractorInterface::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
                ->willReturn(new Service2())
        );

        $extractor->getConstructorInjections(Service1::class)
            ->willReturn([]);
        $extractor->getPropertiesInjections(Service1::class)
            ->willReturn([
                             $injection->reveal(),
                         ]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(false);
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new InjectionService(
            $extractor->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        $this->assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itUsesCacheItemWhenFound()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extrator = $this->prophesize(ExtractorInterface::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
            ->willReturn(new Service2())
        );

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(true);
        $cache->getItem($cacheKey)->willReturn([
            $injection->reveal()
        ]);

        $service = new InjectionService(
            $extrator->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        $this->assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itUsesExtractorWhenCacheItemIsNotAnArray()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $extractor = $this->prophesize(ExtractorInterface::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
            ->willReturn(new Service2())
        );

        $extractor->getConstructorInjections(Service1::class)
            ->willReturn([]);
        $extractor->getPropertiesInjections(Service1::class)
            ->willReturn([
                             $injection->reveal(),
                         ]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(true)->shouldBeCalled();
        $cache->getItem($cacheKey)->willReturn(null)->shouldBeCalled();
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new InjectionService(
            $extractor->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        $this->assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itReturnsFalseWhenNoInjectionsAvaible()
    {
        $cacheKey = $this->buildCacheKey(Service2::class);
        $extractor = $this->prophesize(ExtractorInterface::class);
        $extractor->getConstructorInjections(Service2::class)
            ->willReturn([]);
        $extractor->getPropertiesInjections(Service2::class)
            ->willReturn([]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(false);
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new InjectionService(
            $extractor->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service2::class
        );

        $this->assertFalse($injections);
    }
}
