<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Factory;

use PHPUnit\Framework\TestCase;
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
    public function testItInstancesCacheServiceWithoutConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('get')
            ->with(ModuleConfig::class)
            ->willReturn([]);

        $factory = new CacheServiceFactory();

        $instance = $factory($container);

        self::assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }

    public function testItInstancesCacheServiceWithConfigString(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function ($service) {
                if ($service === ModuleConfig::class) {
                    return [
                        'cache' => Memory::class,
                    ];
                }
                if ($service === Memory::class) {
                    return new Memory();
                }
                return null;
            });

        $factory = new CacheServiceFactory();

        $instance = $factory($container);

        self::assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }

    public function testItInstancesCacheServiceWithConfigCallable(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->once())
            ->method('get')
            ->with(ModuleConfig::class)
            ->willReturn([
                'cache' => static function (ContainerInterface $containerArgument) use ($container) {
                    self::assertSame($container, $containerArgument);
                    return new Memory();
                },
            ]);

        $factory = new CacheServiceFactory();

        $instance = $factory($container);

        self::assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }
}
