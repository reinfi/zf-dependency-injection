<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Laminas\Config\Config;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectConfig;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectConfigTest extends TestCase
{
    public function testItCallsConfigServiceFromContainerWithValue(): void
    {
        $injectConfig = new InjectConfig('reinfi.di.test');

        $configService = $this->createMock(ConfigService::class);
        $configService->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        self::assertTrue($injectConfig($container), 'Invoke should return true');
    }

    public function testItCallsConfigServiceFromPluginManagerWithValue(): void
    {
        $injectConfig = new InjectConfig('reinfi.di.test');

        $configService = $this->createMock(ConfigService::class);
        $configService->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($injectConfig($pluginManager), 'Invoke should return true');
    }

    public function testItReturnsArrayIfPropertyIsSet(): void
    {
        $injectConfig = new InjectConfig('reinfi.di.test', true);

        $config = $this->createMock(Config::class);
        $config->expects($this->once())
            ->method('toArray')
            ->willReturn([true]);

        $configService = $this->createMock(ConfigService::class);
        $configService->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn($config);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        self::assertEquals([true], $injectConfig($container), 'Invoke should return array containing true');
    }
}
