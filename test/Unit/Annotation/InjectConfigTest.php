<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Laminas\Config\Config;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectConfigTest extends TestCase
{
    public function testItCallsConfigServiceFromContainerWithValue(): void
    {
        $inject = new InjectConfig([
            'value' => 'reinfi.di.test',
        ]);

        $configService = $this->createMock(ConfigService::class);
        $configService->expects($this->once())
            ->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        self::assertTrue($inject($container), 'Invoke should return true');
    }

    public function testItCallsConfigServiceFromPluginManagerWithValue(): void
    {
        $inject = new InjectConfig([
            'value' => 'reinfi.di.test',
        ]);

        $configService = $this->createMock(ConfigService::class);
        $configService->expects($this->once())
            ->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($inject($pluginManager), 'Invoke should return true');
    }

    public function testItReturnsArrayIfPropertyIsSet(): void
    {
        $inject = new InjectConfig([
            'value' => 'reinfi.di.test',
            'asArray' => true,
        ]);

        $config = $this->createMock(Config::class);
        $config->expects($this->once())
            ->method('toArray')
            ->willReturn([true]);

        $configService = $this->createMock(ConfigService::class);
        $configService->expects($this->once())
            ->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn($config);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        self::assertEquals([true], $inject($container), 'Invoke should return array containing true');
    }
}
