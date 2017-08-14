<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Service\Service1;
use Reinfi\DependencyInjection\Service\Service2;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class AutoWiringServiceTest extends TestCase
{
    use CacheKeyTrait;

    /**
     * @test
     */
    public function itResolvesConstructorArguments()
    {
        $resolverService = $this->prophesize(ResolverService::class);

        $resolverService->resolve(InjectionService::class)
            ->willReturn([
                new AutoWiring(ExtractorInterface::class),
                new AutoWiring(StorageInterface::class),
             ]);

        $service = new AutoWiringService(
            $resolverService->reveal(),
            new Memory()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $container->has(ExtractorInterface::class)->willReturn(true);
        $container->get(ExtractorInterface::class)
            ->willReturn(
                $this->prophesize(ExtractorInterface::class)->reveal()
            );
        $container->has(StorageInterface::class)->willReturn(true);
        $container->get(StorageInterface::class)
            ->willReturn(new Memory());

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            InjectionService::class
        );

        $this->assertCount(2, $injections);
    }

    /**
     * @test
     */
    public function itUsesCacheItemWhenFound()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $resolverService = $this->prophesize(ResolverService::class);

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

        $service = new AutoWiringService(
            $resolverService->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        $this->assertCount(1, $injections);
        $this->containsOnlyInstancesOf(InjectionInterface::class, $injections);
    }


    /**
     * @test
     */
    public function itReturnsFalseWhenNoInjectionsAvaible()
    {
        $cacheKey = $this->buildCacheKey(Service2::class);
        $resolverService = $this->prophesize(ResolverService::class);
        $resolverService->resolve(Service2::class)
            ->willReturn([]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(false);
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new AutoWiringService(
            $resolverService->reveal(),
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