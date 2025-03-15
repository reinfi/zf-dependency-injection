<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Injection
 */
class AutoWiringTest extends TestCase
{
    public function testItReturnsServiceFromContainer(): void
    {
        $service1 = $this->createMock(Service1::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
            ->with(Service1::class)
            ->willReturn(true);
        $container->method('get')
            ->with(Service1::class)
            ->willReturn($service1);

        $injection = new AutoWiring(Service1::class);

        self::assertInstanceOf(Service1::class, $injection($container));
    }

    public function testItReturnsServiceFromParentLocator(): void
    {
        $service1 = $this->createMock(Service1::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
            ->with(Service1::class)
            ->willReturn(true);
        $container->method('get')
            ->with(Service1::class)
            ->willReturn($service1);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->method('has')
            ->with(Service1::class)
            ->willReturn(false);
        $pluginManager->method('getServiceLocator')
            ->willReturn($container);

        $injection = new AutoWiring(Service1::class);

        self::assertInstanceOf(Service1::class, $injection($pluginManager));
    }

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
            ->with(Service1::class)
            ->willReturn(false);

        $injection = new AutoWiring(Service1::class);

        $injection($container);
    }
}
