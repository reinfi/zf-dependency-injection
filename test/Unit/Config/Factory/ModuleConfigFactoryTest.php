<?php

namespace Reinfi\DependencyInjection\Unit\Config\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\Factory\ModuleConfigFactory;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Unit\Config\Factory
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

        $this->assertInstanceOf(
            Config::class,
            $factory($container->reveal()),
            'Factory should return config class'
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

        $this->assertTrue(
            $config->offsetExists('extractor'),
            'Config should container extractor key'
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
            $config->toArray(),
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