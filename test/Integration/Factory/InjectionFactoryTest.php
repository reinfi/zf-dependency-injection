<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Integration\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\Stdlib\ArrayUtils;
use PHPUnit\Framework\Attributes\Group;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Test\Base\AbstractIntegration;
use Reinfi\DependencyInjection\Test\Service\PluginService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotationConstructor;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Factory
 */
#[Group('integration')]
final class InjectionFactoryTest extends AbstractIntegration
{
    public function testItCreatesServiceWithDependencies(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService($serviceManager, ServiceAnnotation::class, ServiceAnnotation::class);

        self::assertInstanceOf(ServiceAnnotation::class, $instance);
    }

    public function testItCreatesServiceWithDependenciesFromConstructor(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $serviceManager,
            ServiceAnnotationConstructor::class,
            ServiceAnnotationConstructor::class
        );

        self::assertInstanceOf(ServiceAnnotationConstructor::class, $instance);
    }

    public function testItCreatesServiceWithNoInjectionsDefined(): void
    {
        $serviceManager = $this->getServiceManager([
            'service_manager' => [
                'factories' => [
                    Service3::class => InjectionFactory::class,
                ],
            ],
        ]);

        $injectionFactory = new InjectionFactory();

        $instance = $injectionFactory->createService($serviceManager, Service3::class, Service3::class);

        self::assertInstanceOf(Service3::class, $instance);
    }

    public function testItCreatesServiceFromCanonicalName(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService($serviceManager, ServiceAnnotation::class, null);

        self::assertInstanceOf(ServiceAnnotation::class, $instance);
    }

    public function testItCreatesServiceFromPluginManager(): void
    {
        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->atLeastOnce())
            ->method('getServiceLocator')
            ->willReturn($serviceManager);

        $factory = new InjectionFactory();

        $instance = $factory->createService($pluginManager, PluginService::class, null);

        self::assertInstanceOf(PluginService::class, $instance);
    }

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $serviceManager = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $factory->createService($serviceManager, 'NoServiceClass', 'NoServiceClass');
    }

    public function testItResolvesYamlInjections(): void
    {
        $config = ArrayUtils::merge(
            require __DIR__ . '/../../resources/config.php',
            [
                'reinfi.dependencyInjection' => [
                    'extractor' => YamlExtractor::class,
                    'extractor_options' => [
                        'file' => __DIR__ . '/../../resources/services.yml',
                    ],
                ],
            ]
        );
        $container = $this->getServiceManager($config);

        $factory = new InjectionFactory();

        $instance = $factory->createService($container, Service1::class, Service1::class);

        self::assertInstanceOf(Service1::class, $instance);
    }
}
