<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
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
    use ProphecyTrait;

    /**
     * @test
     */
    public function itResolvesResolverServiceLazy()
    {
        $resolverService = $this->prophesize(ResolverServiceInterface::class);
        $resolverService->resolve('test', null)
            ->willReturn([]);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ResolverService::class)
            ->willReturn($resolverService->reveal())
            ->shouldBeCalled();

        $service = new LazyResolverService($container->reveal());

        $service->resolve('test');
    }

    /**
     * @test
     */
    public function itResolvesResolverServiceLazyWithOptions()
    {
        $options = ['foo' => 'bar'];

        $resolverService = $this->prophesize(ResolverServiceInterface::class);
        $resolverService->resolve('test', $options)
                        ->willReturn([]);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ResolverService::class)
                  ->willReturn($resolverService->reveal())
                  ->shouldBeCalled();

        $service = new LazyResolverService($container->reveal());

        $service->resolve('test', $options);
    }

    /**
     * @test
     */
    public function itResolvesResolverServiceOnlyOnce()
    {
        $resolverService = $this->prophesize(ResolverServiceInterface::class);
        $resolverService->resolve('test', null)
            ->willReturn([]);
        $resolverService->resolve('test2', null)
            ->willReturn([]);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ResolverService::class)
            ->willReturn($resolverService->reveal())
            ->shouldBeCalledTimes(1);

        $service = new LazyResolverService($container->reveal());

        $service->resolve('test');
        $service->resolve('test2');
    }
}
