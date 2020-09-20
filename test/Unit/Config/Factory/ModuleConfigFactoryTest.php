<?php

namespace Reinfi\DependencyInjection\Test\Unit\Config\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\Factory\ModuleConfigFactory;
use Reinfi\DependencyInjection\Config\ModuleConfig;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Config\Factory
 */
class ModuleConfigFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsModuleConfig()
    {
        $factory = new ModuleConfigFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([ ModuleConfig::CONFIG_KEY => [] ]);

        $this->assertIsArray(
            $factory($container->reveal()),
            'Factory should return array'
        );
    }

    /**
     * @test
     */
    public function itReturnsModuleConfigData()
    {
        $factory = new ModuleConfigFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([ ModuleConfig::CONFIG_KEY => [ 'extractor' => '' ] ]);

        $config = $factory($container->reveal());

        $this->assertArrayHasKey(
            'extractor',
            $config,
            'Config should contain extractor key'
        );
    }

    /**
     * @test
     */
    public function itReturnsEmptyConfig()
    {
        $factory = new ModuleConfigFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([]);

        $config = $factory($container->reveal());

        $this->assertCount(
            0,
            $config,
            'Config should be empty'
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfModuleConfigIsNotArray()
    {
        $this->expectException(\InvalidArgumentException::class);

        $factory = new ModuleConfigFactory();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn([ ModuleConfig::CONFIG_KEY => true ]);

        $factory($container->reveal());
    }
}
