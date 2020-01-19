<?php

namespace Reinfi\DependencyInjection\Test\Integration\Factory;

use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Test\Integration\AbstractIntegrationTest;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Test\Service\PluginService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotationConstructor;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\Stdlib\ArrayUtils;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Factory
 *
 * @group integration
 */
class InjectionFactoryTest extends AbstractIntegrationTest
{
    /**
     * @test
     */
    public function itCreatesServiceWithDependencies()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container,
            ServiceAnnotation::class,
            ServiceAnnotation::class
        );

        $this->assertInstanceOf(
            ServiceAnnotation::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithDependenciesFromConstructor()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container,
            ServiceAnnotationConstructor::class,
            ServiceAnnotationConstructor::class
        );

        $this->assertInstanceOf(
            ServiceAnnotationConstructor::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceWithNoInjectionsDefined()
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

        $this->assertInstanceOf(
            Service3::class,
            $instance
        );
    }

    /**
     * @test
     */
    public function itCreatesServiceFromCanonicalName()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new InjectionFactory();

        $instance = $factory->createService(
            $container,
            ServiceAnnotation::class,
            null
        );

        $this->assertInstanceOf(
            ServiceAnnotation::class,
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

        $factory = new InjectionFactory();

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

        $factory = new InjectionFactory();

        $factory->createService(
            $container,
            'NoServiceClass',
            'NoServiceClass'
        );
    }

    /**
     * @test
     */
    public function itResolvesYamlInjections()
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

        $this->assertInstanceOf(
            Service1::class,
            $instance
        );
    }
}
