<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Injection
 */
class AutoWiringPluginManagerTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsServiceFromContainer(): void
    {
        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $pluginManager->get(Service1::class)
            ->willReturn($service1->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('PluginManager')
            ->willReturn($pluginManager->reveal());

        $injection = new AutoWiringPluginManager(
            'PluginManager',
            Service1::class
        );

        self::assertInstanceOf(
            Service1::class,
            $injection($container->reveal())
        );
    }

    public function testItReturnsServiceFromParentLocator(): void
    {
        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $pluginManager->get(Service1::class)
            ->willReturn($service1->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('PluginManager')
            ->willReturn($pluginManager->reveal());

        $otherPluginManager = $this->prophesize(AbstractPluginManager::class);
        $otherPluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $injection = new AutoWiringPluginManager(
            'PluginManager',
            Service1::class
        );

        self::assertInstanceOf(
            Service1::class,
            $injection($otherPluginManager->reveal())
        );
    }

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(false);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('PluginManager')
            ->willReturn($pluginManager->reveal());

        $injection = new AutoWiringPluginManager(
            'PluginManager',
            Service1::class
        );

        $injection($container->reveal());
    }
}
