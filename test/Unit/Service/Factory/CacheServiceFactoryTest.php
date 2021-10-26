<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Cache\Memory;
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
            ->willReturn(['cache' => Memory::class]);
        $container->get(Memory::class)
            ->willReturn(new Memory())
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
            ->willReturn(['cache' => static function (ContainerInterface $containerArgument) use ($container){
                self::assertSame($container->reveal(), $containerArgument);
                return new Memory();
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
