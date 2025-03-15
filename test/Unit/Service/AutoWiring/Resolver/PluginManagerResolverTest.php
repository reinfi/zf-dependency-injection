<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Filter\ToInt;
use Laminas\Form\Element\Textarea;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Digits;
use Laminas\View\Helper\Url;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\ServiceInterface;
use Reinfi\DependencyInjection\Test\Service\ServiceWithInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class PluginManagerResolverTest extends TestCase
{
    /**
     * @dataProvider getPluginManagerData
     */
    public function testItReturnsInjectionInterfaceForPluginManager(
        string $serviceClass,
        string $pluginManager
    ): void {
        $pluginManagerClass = $this->createMock(ContainerInterface::class);
        $pluginManagerClass->method('has')
            ->with($serviceClass)
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with($pluginManager)
            ->willReturn($pluginManagerClass);

        $resolver = new PluginManagerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')
            ->willReturn($serviceClass);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')
            ->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(AutoWiringPluginManager::class, $injection);
    }

    /**
     * @dataProvider getPluginManagerData
     */
    public function testItReturnsServiceAndPluginManager(string $serviceClass, string $pluginManager): void
    {
        $pluginManagerClass = $this->createMock(ContainerInterface::class);
        $pluginManagerClass->method('has')
            ->with($serviceClass)
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with($pluginManager)
            ->willReturn($pluginManagerClass);

        $resolver = new PluginManagerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')
            ->willReturn($serviceClass);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')
            ->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertNotNull($injection, 'injection could not resolved');

        $reflectionClass = new ReflectionClass($injection);
        $property = $reflectionClass->getProperty('serviceName');
        $property->setAccessible(true);

        self::assertEquals($serviceClass, $property->getValue($injection));

        $property = $reflectionClass->getProperty('pluginManager');
        $property->setAccessible(true);

        self::assertEquals($pluginManager, $property->getValue($injection));
    }

    public function testItResolvesAdditionalInterfaceMappings(): void
    {
        PluginManagerResolver::addMapping(ServiceInterface::class, 'InjectionManager');

        $pluginManagerClass = $this->createMock(ContainerInterface::class);
        $pluginManagerClass->method('has')
            ->with(ServiceWithInterface::class)
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with('InjectionManager')
            ->willReturn($pluginManagerClass);
        $resolver = new PluginManagerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')
            ->willReturn(ServiceWithInterface::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')
            ->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertNotNull($injection, 'injection could not resolved');

        $reflCass = new ReflectionClass($injection);
        $property = $reflCass->getProperty('pluginManager');
        $property->setAccessible(true);

        self::assertEquals('InjectionManager', $property->getValue($injection));
    }

    public function testItReturnsNullIfNoPluginManagerFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $resolver = new PluginManagerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')
            ->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')
            ->willReturn($type);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $resolver = new PluginManagerResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')
            ->willReturn(null);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }

    public static function getPluginManagerData(): array
    {
        return [
            [ReflectionHydrator::class, 'HydratorManager'],
            [Url::class, 'ViewHelperManager'],
            [Digits::class, 'ValidatorManager'],
            [ToInt::class, 'FilterManager'],
            [InputFilter::class, 'InputFilterManager'],
            [Textarea::class, 'FormElementManager'],
        ];
    }
}
