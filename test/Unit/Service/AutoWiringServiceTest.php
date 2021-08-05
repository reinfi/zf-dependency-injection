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
    public function itResolvesConstructorArguments(): void
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
        $cache->has($cacheKey)->willReturn(false);
        $cache->set($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new AutoWiringService(
            $resolver->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        self::assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itResolvesConstructorArgumentsWithOptions(): void
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
        $cache->has($cacheKey)->willReturn(false);
        $cache->set($cacheKey, Argument::type('array'))->willReturn(true);

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

        self::assertCount(2, $injections);
    }

    /**
     * @test
     */
    public function itUsesCacheItemWhenFound(): void
    {
        $cacheKey = $this->buildCacheKey(Service1::class);
        $resolverService = $this->prophesize(ResolverService::class);

        $injection = $this->prophesize(InjectionInterface::class);
        $injection->addMethodProphecy(
            (new MethodProphecy($injection, '__invoke', [Argument::type(ContainerInterface::class)]))
            ->willReturn(new Service2())
        );

        $cache = $this->prophesize(CacheService::class);
        $cache->has($cacheKey)->willReturn(true);
        $cache->get($cacheKey)->willReturn([
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

        self::assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itDoesNotCacheOptions(): void
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
        $cache->set($cacheKey, Argument::type('array'))->shouldNotBeCalled();
        $cache->has($cacheKey)->shouldNotBeCalled();
        $cache->get($cacheKey)->shouldNotBeCalled();

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

        self::assertCount(2, $injections);
    }

    /**
     * @test
     */
    public function itUsesResolverWhenCacheItemIsNotAnArray(): void
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
        $cache->has($cacheKey)->willReturn(true)->shouldBeCalled();
        $cache->get($cacheKey)->willReturn(null)->shouldBeCalled();
        $cache->set($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new AutoWiringService(
            $resolverService->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service1::class
        );

        self::assertCount(1, $injections);
    }

    /**
     * @test
     */
    public function itReturnsFalseWhenNoInjectionsAvailable(): void
    {
        $cacheKey = $this->buildCacheKey(Service2::class);
        $resolverService = $this->prophesize(ResolverService::class);
        $resolverService->resolve(Service2::class, null)
            ->willReturn([]);

        $cache = $this->prophesize(CacheService::class);
        $cache->has($cacheKey)->willReturn(false);
        $cache->set($cacheKey, Argument::type('array'))->willReturn(true);

        $service = new AutoWiringService(
            $resolverService->reveal(),
            $cache->reveal()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            Service2::class
        );

        self::assertNull(
            $injections,
            'Return value should be null if service has no injections'
        );
    }
}
