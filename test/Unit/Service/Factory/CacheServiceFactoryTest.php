<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Factory;

use Cache\Adapter\PHPArray\ArrayCachePool;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Factory\CacheServiceFactory;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Factory
 */
class CacheServiceFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itInstancesCacheServiceWithoutConfig(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn([]);

        $factory = new CacheServiceFactory();

        $instance = $factory($container->reveal());

        self::assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }

    /**
     * @test
     */
    public function itInstancesCacheServiceWithConfigString(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn(['cache' => ArrayCachePool::class]);
        $container->get(ArrayCachePool::class)
            ->willReturn(new ArrayCachePool())
            ->shouldBeCalled();

        $factory = new CacheServiceFactory();

        $instance = $factory($container->reveal());

        self::assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }

    /**
     * @test
     */
    public function itInstancesCacheServiceWithConfigCallable(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn(['cache' => function () {
                return new ArrayCachePool();
            }]);

        $factory = new CacheServiceFactory();

        $instance = $factory($container->reveal());

        self::assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }
}
