<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Factory
 */
final class InjectionFactoryTest extends TestCase
{
    public function testItCreatesServiceWithInjections(): void
    {
        $service = $this->createMock(InjectionService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with($this->isInstanceOf(ContainerInterface::class), Service1::class)
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn($service);

        $injectionFactory = new InjectionFactory();

        $instance = $injectionFactory->createService($container, Service1::class, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceFromCanonicalName(): void
    {
        $service = $this->createMock(InjectionService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with($this->isInstanceOf(ContainerInterface::class), Service1::class)
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn($service);

        $injectionFactory = new InjectionFactory();

        $instance = $injectionFactory->createService($container, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceFromPluginManager(): void
    {
        $service = $this->createMock(InjectionService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with($this->isInstanceOf(ContainerInterface::class), Service1::class)
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn($service);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        $injectionFactory = new InjectionFactory();

        $instance = $injectionFactory->createService($pluginManager, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceWithNoInjections(): void
    {
        $service = $this->createMock(InjectionService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with($this->isInstanceOf(ContainerInterface::class), Service2::class)
            ->willReturn(false);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn($service);

        $injectionFactory = new InjectionFactory();

        $instance = $injectionFactory->createService($container, Service2::class);

        self::assertInstanceOf(Service2::class, $instance);
    }

    public function testItThrowsExceptionIfClassNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->createMock(ServiceLocatorInterface::class);

        $injectionFactory = new InjectionFactory();

        $injectionFactory->createService($container);
    }
}
