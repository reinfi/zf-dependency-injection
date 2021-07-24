<?php

namespace Reinfi\DependencyInjection\Test\Integration\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Prophecy\PhpUnit\ProphecyTrait;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Test\Integration\AbstractIntegrationTest;
use Reinfi\DependencyInjection\Test\Service\PluginService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefault;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefaultUsingConstant;
use Reinfi\DependencyInjection\Test\Service\ServiceContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Factory
 *
 * @group integration
 */
class AutoWiringFactoryTest extends AbstractIntegrationTest
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itCreatesServiceWithDependencies(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            Service1::class,
            Service1::class
        );

        self::assertInstanceOf(
            Service1::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithContainerAsDependency(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            ServiceContainer::class,
            ServiceContainer::class
        );

        self::assertInstanceOf(
            ServiceContainer::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithNoDependencies(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            Service3::class,
            Service3::class
        );

        self::assertInstanceOf(
            Service3::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithBuiltInType(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            ServiceBuildInTypeWithDefault::class,
            ServiceBuildInTypeWithDefault::class
        );

        self::assertInstanceOf(
            ServiceBuildInTypeWithDefault::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithBuiltInTypeUsingConstantAsDefault(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            ServiceBuildInTypeWithDefaultUsingConstant::class,
            ServiceBuildInTypeWithDefaultUsingConstant::class
        );

        self::assertInstanceOf(
            ServiceBuildInTypeWithDefaultUsingConstant::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceFromPluginManager(): void
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

        self::assertInstanceOf(
            PluginService::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfServiceNotFound(): void
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
