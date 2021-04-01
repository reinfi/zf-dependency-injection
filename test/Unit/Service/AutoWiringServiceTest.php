<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
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
    use ProphecyTrait;
    use CacheKeyTrait;

    /**
     * @test
     */
    public function itResolvesConstructorArguments()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $resolver = $this->prophesize(ResolverService::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
                ->willReturn(new Service2())
                ->shouldBeCalled()
        );

        $resolver->resolve(Service1::class, null)
            ->willReturn([
                 $injection->reveal(),
             ]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(false);
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new AutoWiringService(
            $resolver->reveal(),
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
    public function itResolvesConstructorArgumentsWithOptions()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $resolver = $this->prophesize(ResolverService::class);
        $options = ['foo' => 'bar'];

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
                ->willReturn(new Service2())
                ->shouldBeCalled()
        );

        $optionsInjection = $this->prophesize(InjectionInterface::class);
        $optionsInjection->addMethodProphecy(
            (new MethodProphecy($optionsInjection, '__invoke', [Argument::type(ContainerInterface::class)]))
                ->willReturn('bar')
                ->shouldBeCalled()
        );

        $resolver->resolve(Service1::class, $options)
            ->willReturn([
                 $injection->reveal(),
                 $optionsInjection->reveal(),
             ]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(false);
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new AutoWiringService(
            $resolver->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class,
            $options
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
    }

    /**
     * @test
     */
    public function itDoesNotCacheOptions()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $resolverService = $this->prophesize(ResolverService::class);
        $options = ['foo' => 'bar'];

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
            ->willReturn(new Service2())
        );

        $optionsInjection = $this->prophesize(InjectionInterface::class);
        $optionsInjection->addMethodProphecy(
            (new MethodProphecy($optionsInjection, '__invoke', [Argument::type(ContainerInterface::class)]))
                ->willReturn('bar')
                ->shouldBeCalled()
        );

        $resolverService->resolve(Service1::class, $options)
            ->willReturn([
                $injection->reveal(),
                $optionsInjection->reveal(),
            ]);

        $cache = $this->prophesize(CacheService::class);
        $cache->setItem($cacheKey, Argument::type('array'))->shouldNotBeCalled();
        $cache->hasItem($cacheKey)->shouldNotBeCalled();
        $cache->getItem($cacheKey)->shouldNotBeCalled();

        $service = new AutoWiringService(
            $resolverService->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class,
            $options
        );

        $this->assertCount(2, $injections);
    }

    /**
     * @test
     */
    public function itUsesResolverWhenCacheItemIsNotAnArray()
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $resolverService = $this->prophesize(ResolverService::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
            ->willReturn(new Service2())
        );

        $resolverService->resolve(Service1::class, null)
            ->willReturn([$injection->reveal()]);

        $cache = $this->prophesize(CacheService::class);
        $cache->hasItem($cacheKey)->willReturn(true)->shouldBeCalled();
        $cache->getItem($cacheKey)->willReturn(null)->shouldBeCalled();
        $cache->setItem($cacheKey, Argument::type('array'))->willReturn(true);

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
    }

    /**
     * @test
     */
    public function itReturnsFalseWhenNoInjectionsAvaible()
    {
        $cacheKey = $this->buildCacheKey(Service2::class);
        $resolverService = $this->prophesize(ResolverService::class);
        $resolverService->resolve(Service2::class, null)
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

        $this->assertNull(
            $injections,
            'Return value should be null if service has no injections'
        );
    }
}
