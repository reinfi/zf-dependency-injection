<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Config\Factory;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\Factory\ModuleConfigFactory;
use Reinfi\DependencyInjection\Config\ModuleConfig;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Config\Factory
 */
class ModuleConfigFactoryTest extends TestCase
{
    public function testItReturnsModuleConfig(): void
    {
        $factory = new ModuleConfigFactory();

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([
                ModuleConfig::CONFIG_KEY => [],
            ]);

        self::assertIsArray($factory($container), 'Factory should return array');
    }

    public function testItReturnsModuleConfigData(): void
    {
        $factory = new ModuleConfigFactory();

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([
                ModuleConfig::CONFIG_KEY => [
                    'extractor' => '',
                ],
            ]);

        $config = $factory($container);

        self::assertArrayHasKey('extractor', $config, 'Config should contain extractor key');
    }

    public function testItReturnsEmptyConfig(): void
    {
        $factory = new ModuleConfigFactory();

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([]);

        $config = $factory($container);

        self::assertCount(0, $config, 'Config should be empty');
    }

    public function testItThrowsExceptionIfModuleConfigIsNotArray(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new ModuleConfigFactory();

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn([
                ModuleConfig::CONFIG_KEY => true,
            ]);

        $factory($container);
    }
}
