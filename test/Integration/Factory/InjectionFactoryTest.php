<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Integration\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\Stdlib\ArrayUtils;
use Prophecy\PhpUnit\ProphecyTrait;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Test\Integration\AbstractIntegration;
use Reinfi\DependencyInjection\Test\Service\PluginService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotationConstructor;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Factory
 *
 * @group integration
 */
class InjectionFactoryTest extends AbstractIntegration
{
    use ProphecyTrait;

    public function testItCreatesServiceWithDependencies(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container,
            ServiceAnnotation::class,
            ServiceAnnotation::class
        );

        self::assertInstanceOf(
            ServiceAnnotation::class,
            $instance
        );
    }

    public function testItCreatesServiceWithDependenciesFromConstructor(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container,
            ServiceAnnotationConstructor::class,
            ServiceAnnotationConstructor::class
        );

        self::assertInstanceOf(
            ServiceAnnotationConstructor::class,
            $instance
        );
    }

    public function testItCreatesServiceWithNoInjectionsDefined(): void
    {
        $container = $this->getServiceManager([
            'service_manager' => [
                'factories' => [
                    Service3::class => InjectionFactory::class,
                ],
            ],
        ]);

        $factory = new InjectionFactory();

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

    public function testItCreatesServiceFromCanonicalName(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container,
            ServiceAnnotation::class,
            null
        );

        self::assertInstanceOf(
            ServiceAnnotation::class,
            $instance
        );
    }

    public function testItCreatesServiceFromPluginManager(): void
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container)
            ->shouldBeCalled();

        $factory = new InjectionFactory();

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

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(InvalidServiceException::class);

        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $factory->createService(
            $container,
            'NoServiceClass',
            'NoServiceClass'
        );
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
}
