<?php

namespace Reinfi\DependencyInjection\Unit\Service\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Factory\CacheServiceFactory;
use Zend\Cache\Storage\Adapter\Memory;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\Factory
 */
class CacheServiceFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider cacheServiceOptionsProvider
     *
     * @param array $options
     */
    public function itInstancesCacheService(array $options)
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