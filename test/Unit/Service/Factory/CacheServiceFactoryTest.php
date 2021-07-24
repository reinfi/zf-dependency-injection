<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Factory;

use Laminas\Cache\Storage\Adapter\Memory;
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
     * @dataProvider cacheServiceOptionsProvider
     *
     * @param array $options
     */
    public function itInstancesCacheService(array $options): void
    {
        $moduleConfig = $options;

        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig);

        $factory = new CacheServiceFactory();

        $instance = $factory($container->reveal());

        $this->assertInstanceOf(
            CacheService::class,
            $instance,
            'factory should return instance of ' . CacheService::class
        );
    }

    /**
     * @return array
     */
    public function cacheServiceOptionsProvider(): array
    {
        return [
            [
                [],
            ],
            [
                [ 'cache' => Memory::class ],
            ],
            [
                [
                    'cache'         => Memory::class,
                    'cache_options' => [],
                ],
            ],
            [
                [
                    'cache'         => Memory::class,
                    'cache_plugins' => [],
                ],
            ],
            [
                [
                    'cache'         => Memory::class,
                    'cache_options' => [],
                    'cache_plugins' => [],
                ],
            ],
        ];
    }
}
