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
    use ProphecyTrait;

    public function testItCreatesServiceWithInjections(): void
    {
        $service = $this->prophesize(AutoWiringService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class,
            null
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(AutoWiringService::class)
            ->willReturn($service->reveal());

        $factory = new AutoWiringFactory();

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

    public function testItCreatesServiceWithInjectionsWithOptions(): void
    {
        $options = [
            'foo' => 'bar',
        ];
        $service = $this->prophesize(AutoWiringService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class,
            $options
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(AutoWiringService::class)
            ->willReturn($service->reveal());

        $factory = new AutoWiringFactory();

        $instance = $factory($container->reveal(), Service1::class, $options);

        self::assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    public function testItCreatesServiceFromCanonicalName(): void
    {
        $service = $this->prophesize(AutoWiringService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class,
            null
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(AutoWiringService::class)
            ->willReturn($service->reveal());

        $factory = new AutoWiringFactory();

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
        $service = $this->prophesize(AutoWiringService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service1::class,
            null
        )->willReturn([new Service2(), new Service3()]);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(AutoWiringService::class)
            ->willReturn($service->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $factory = new AutoWiringFactory();

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
        $service = $this->prophesize(AutoWiringService::class);
        $service->resolveConstructorInjection(
            Argument::type(ContainerInterface::class),
            Service2::class,
            null
        )->willReturn(null);

        $container = $this->prophesize(ServiceLocatorInterface::class);
        $container->get(AutoWiringService::class)
            ->willReturn($service->reveal());

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container->reveal(),
            Service2::class
        );

        self::assertInstanceOf(
            Service2::class,
            $instance
        );
    }

    public function testItThrowsExceptionIfClassNotSet(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->prophesize(ServiceLocatorInterface::class);

        $factory = new AutoWiringFactory();

        $factory->createService(
            $container->reveal()
        );
    }

    public function testItThrowsExceptionIfClassNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->prophesize(ServiceLocatorInterface::class);

        $factory = new AutoWiringFactory();

        $factory->createService(
            $container->reveal(),
            'No\Existing\ClassName',
            'No\Existing\ClassName'
        );
    }
}
