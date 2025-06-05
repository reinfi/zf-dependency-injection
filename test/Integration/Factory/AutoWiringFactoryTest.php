<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Integration\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use PHPUnit\Framework\Attributes\Group;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Test\Base\AbstractIntegration;
use Reinfi\DependencyInjection\Test\Service\PluginService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefault;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefaultUsingConstant;
use Reinfi\DependencyInjection\Test\Service\ServiceContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Factory
 */
#[Group('integration')]
final class AutoWiringFactoryTest extends AbstractIntegration
{
    public function testItCreatesServiceWithDependencies(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($serviceManager, Service1::class, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }

    public function testItCreatesServiceWithContainerAsDependency(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($serviceManager, ServiceContainer::class, ServiceContainer::class);

        self::assertInstanceOf(ServiceContainer::class, $instance);
    }

    public function testItCreatesServiceWithNoDependencies(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($serviceManager, Service3::class, Service3::class);

        self::assertInstanceOf(Service3::class, $instance);
    }

    public function testItCreatesServiceWithBuiltInType(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $serviceManager,
            ServiceBuildInTypeWithDefault::class,
            ServiceBuildInTypeWithDefault::class
        );

        self::assertInstanceOf(ServiceBuildInTypeWithDefault::class, $instance);
    }

    public function testItCreatesServiceWithBuiltInTypeUsingConstantAsDefault(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $serviceManager,
            ServiceBuildInTypeWithDefaultUsingConstant::class,
            ServiceBuildInTypeWithDefaultUsingConstant::class
        );

        self::assertInstanceOf(ServiceBuildInTypeWithDefaultUsingConstant::class, $instance);
    }

    public function testItCreatesServiceFromPluginManager(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->atLeastOnce())
            ->method('getServiceLocator')
            ->willReturn($serviceManager);
        $pluginManager->expects($this->once())
            ->method('has')
            ->with(Service2::class)
            ->willReturn(false);

        $factory = new AutoWiringFactory();

        $instance = $factory->createService($pluginManager, PluginService::class, null);

        self::assertInstanceOf(PluginService::class, $instance);
    }

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $factory->createService($serviceManager, 'NoServiceClass', 'NoServiceClass');
    }
}
