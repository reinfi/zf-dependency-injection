<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Injection
 */
class AutoWiringPluginManagerTest extends TestCase
{
    public function testItReturnsServiceFromContainer(): void
    {
        $service1 = $this->createMock(Service1::class);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->method('has')
            ->with(Service1::class)
            ->willReturn(true);
        $pluginManager->method('get')
            ->with(Service1::class)
            ->willReturn($service1);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with('PluginManager')
            ->willReturn($pluginManager);

        $injection = new AutoWiringPluginManager('PluginManager', Service1::class);

        self::assertInstanceOf(Service1::class, $injection($container));
    }

    public function testItReturnsServiceFromParentLocator(): void
    {
        $service1 = $this->createMock(Service1::class);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->method('has')
            ->with(Service1::class)
            ->willReturn(true);
        $pluginManager->method('get')
            ->with(Service1::class)
            ->willReturn($service1);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with('PluginManager')
            ->willReturn($pluginManager);

        $otherPluginManager = $this->createMock(AbstractPluginManager::class);
        $otherPluginManager->method('getServiceLocator')
            ->willReturn($container);

        $injection = new AutoWiringPluginManager('PluginManager', Service1::class);

        self::assertInstanceOf(Service1::class, $injection($otherPluginManager));
    }

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->method('has')
            ->with(Service1::class)
            ->willReturn(false);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with('PluginManager')
            ->willReturn($pluginManager);

        $injection = new AutoWiringPluginManager('PluginManager', Service1::class);

        $injection($container);
    }
}
