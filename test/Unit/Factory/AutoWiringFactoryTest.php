<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Factory
 */
class AutoWiringFactoryTest extends TestCase
{
    public function testItCreatesServiceWithInjections(): void
    {
        $service = $this->createMock(AutoWiringService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with(
                $this->isInstanceOf(ContainerInterface::class),
                $this->equalTo(Service1::class),
                $this->equalTo(null)
            )
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(AutoWiringService::class)
            ->willReturn($service);

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($container, Service1::class, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceWithInjectionsWithOptions(): void
    {
        $options = [
            'foo' => 'bar',
        ];
        $service = $this->createMock(AutoWiringService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with(
                $this->isInstanceOf(ContainerInterface::class),
                $this->equalTo(Service1::class),
                $this->equalTo($options)
            )
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(AutoWiringService::class)
            ->willReturn($service);

        $factory = new AutoWiringFactory();

        $instance = $factory($container, Service1::class, $options);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceFromCanonicalName(): void
    {
        $service = $this->createMock(AutoWiringService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with(
                $this->isInstanceOf(ContainerInterface::class),
                $this->equalTo(Service1::class),
                $this->equalTo(null)
            )
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(AutoWiringService::class)
            ->willReturn($service);

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($container, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceFromPluginManager(): void
    {
        $service = $this->createMock(AutoWiringService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with(
                $this->isInstanceOf(ContainerInterface::class),
                $this->equalTo(Service1::class),
                $this->equalTo(null)
            )
            ->willReturn([new Service2(), new Service3()]);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(AutoWiringService::class)
            ->willReturn($service);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($pluginManager, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceWithNoInjections(): void
    {
        $service = $this->createMock(AutoWiringService::class);
        $service->expects($this->once())
            ->method('resolveConstructorInjection')
            ->with(
                $this->isInstanceOf(ContainerInterface::class),
                $this->equalTo(Service2::class),
                $this->equalTo(null)
            )
            ->willReturn(null);

        $container = $this->createMock(ServiceLocatorInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(AutoWiringService::class)
            ->willReturn($service);

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($container, Service2::class);

        self::assertInstanceOf(Service2::class, $instance);
    }

    public function testItThrowsExceptionIfClassNotSet(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->createMock(ServiceLocatorInterface::class);

        $factory = new AutoWiringFactory();

        $factory->createService($container);
    }

    public function testItThrowsExceptionIfClassNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->createMock(ServiceLocatorInterface::class);

        $factory = new AutoWiringFactory();

        $factory->createService($container, 'No\Existing\ClassName', 'No\Existing\ClassName');
    }
}
