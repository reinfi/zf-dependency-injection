<?php

namespace Reinfi\DependencyInjection\Test\Integration\Factory;

use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Test\Integration\AbstractIntegrationTest;
use Reinfi\DependencyInjection\Test\Service\PluginService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefault;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefaultUsingConstant;
use Reinfi\DependencyInjection\Test\Service\ServiceContainer;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Factory
 *
 * @group integration
 */
class AutoWiringFactoryTest extends AbstractIntegrationTest
{
    /**
     * @test
     */
    public function itCreatesServiceWithDependencies()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
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
    public function itCreatesServiceWithContainerAsDependency()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            ServiceContainer::class,
            ServiceContainer::class
        );

        $this->assertInstanceOf(
            ServiceContainer::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithNoDependencies()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            Service3::class,
            Service3::class
        );

        $this->assertInstanceOf(
            Service3::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithBuiltInType()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            ServiceBuildInTypeWithDefault::class,
            ServiceBuildInTypeWithDefault::class
        );

        $this->assertInstanceOf(
            ServiceBuildInTypeWithDefault::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithBuiltInTypeUsingConstantAsDefault()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            ServiceBuildInTypeWithDefaultUsingConstant::class,
            ServiceBuildInTypeWithDefaultUsingConstant::class
        );

        $this->assertInstanceOf(
            ServiceBuildInTypeWithDefaultUsingConstant::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceFromPluginManager()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container)
            ->shouldBeCalled();
        $pluginManager->has(Service2::class)
            ->willReturn(false)
            ->shouldBeCalled();

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $pluginManager->reveal(),
            PluginService::class,
            null
        );

        $this->assertInstanceOf(
            PluginService::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfServiceNotFound()
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $factory->createService(
            $container,
            'NoServiceClass',
            'NoServiceClass'
        );
    }
}
