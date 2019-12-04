<?php

namespace Reinfi\DependencyInjection\Test\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Factory
 */
class AutoWiringFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itCreatesServiceWithInjections()
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

        $this->assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithInjectionsWithOptions()
    {
        $options = ['foo' => 'bar'];
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

        $this->assertInstanceOf(
            Service2::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfClassNotSet()
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->prophesize(ServiceLocatorInterface::class);

        $factory = new AutoWiringFactory();

        $factory->createService(
            $container->reveal()
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfClassNotFound()
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
