<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Factory
 */
class InjectionFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testItCreatesServiceWithInjections(): void
    {
        $service = $this->prophesize(InjectionService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(InjectionService::class)
            ->willReturn($service->reveal());

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container->reveal(),
            Service1::class,
            Service1::class
        );

        self::assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    public function testItCreatesServiceFromCanonicalName(): void
    {
        $service = $this->prophesize(InjectionService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(InjectionService::class)
            ->willReturn($service->reveal());

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container->reveal(),
            Service1::class
        );

        self::assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    public function testItCreatesServiceFromPluginManager(): void
    {
        $service = $this->prophesize(InjectionService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(InjectionService::class)
            ->willReturn($service->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $pluginManager->reveal(),
            Service1::class
        );

        self::assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    public function testItCreatesServiceWithNoInjections(): void
    {
        $service = $this->prophesize(InjectionService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service2::class
        )->willReturn(false);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(InjectionService::class)
            ->willReturn($service->reveal());

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container->reveal(),
            Service2::class
        );

        self::assertInstanceOf(
            Service2::class,
            $instance
        );
    }

    public function testItThrowsExceptionIfClassNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->prophesize(ServiceLocatorInterface::class);

        $factory = new InjectionFactory();

        $factory->createService(
            $container->reveal()
        );
    }
}
