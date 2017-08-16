<?php

namespace Reinfi\DependencyInjection\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Service\Service1;
use Reinfi\DependencyInjection\Service\Service2;
use Reinfi\DependencyInjection\Service\Service3;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Unit\Factory
 */
class InjectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesServiceWithInjections()
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

        $this->assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceFromCanonicalName()
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

        $this->assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceFromPluginManager()
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

        $this->assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithNoInjections()
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

        $this->assertInstanceOf(
            Service2::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfClassNotFound()
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->prophesize(ServiceLocatorInterface::class);

        $factory = new InjectionFactory();

        $factory->createService(
            $container->reveal()
        );
    }
}