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
use Prophecy\PhpUnit\ProphecyTrait;
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
    use ProphecyTrait;

    /**
     * @dataProvider getPluginManagerData
     */
    public function testItReturnsInjectionInterfaceForPluginManager(
        string $serviceClass,
        string $pluginManager
    ): void {
        $pluginManagerClass = $this->prophesize(ContainerInterface::class);
        $pluginManagerClass->has($serviceClass)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($pluginManager)
            ->willReturn($pluginManagerClass->reveal());

        $resolver = new PluginManagerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn($serviceClass);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertInstanceOf(AutoWiringPluginManager::class, $injection);
    }

    /**
     * @dataProvider getPluginManagerData
     */
    public function testItReturnsServiceAndPluginManager(
        string $serviceClass,
        string $pluginManager
    ): void {
        $pluginManagerClass = $this->prophesize(ContainerInterface::class);
        $pluginManagerClass->has($serviceClass)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($pluginManager)
            ->willReturn($pluginManagerClass->reveal());

        $resolver = new PluginManagerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn($serviceClass);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertNotNull(
            $injection,
            'injection could not resolved'
        );

        $reflectionClass = new ReflectionClass($injection);
        $property = $reflectionClass->getProperty('serviceName');
        $property->setAccessible(true);

        self::assertEquals(
            $serviceClass,
            $property->getValue($injection)
        );

        $property = $reflectionClass->getProperty('pluginManager');
        $property->setAccessible(true);

        self::assertEquals(
            $pluginManager,
            $property->getValue($injection)
        );
    }

    public function testItResolvesAdditionalInterfaceMappings(): void
    {
        PluginManagerResolver::addMapping(
            ServiceInterface::class,
            'InjectionManager'
        );

        $pluginManagerClass = $this->prophesize(ContainerInterface::class);
        $pluginManagerClass->has(ServiceWithInterface::class)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('InjectionManager')
            ->willReturn($pluginManagerClass->reveal());
        $resolver = new PluginManagerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(ServiceWithInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertNotNull(
            $injection,
            'injection could not resolved'
        );

        $reflCass = new ReflectionClass($injection);
        $property = $reflCass->getProperty('pluginManager');
        $property->setAccessible(true);

        self::assertEquals(
            'InjectionManager',
            $property->getValue($injection)
        );
    }

    public function testItReturnsNullIfNoPluginManagerFound(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new PluginManagerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new PluginManagerResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        self::assertNull($injection);
    }

    public function getPluginManagerData(): array
    {
        return [
            [
                ReflectionHydrator::class,
                'HydratorManager',
            ],
            [
                Url::class,
                'ViewHelperManager',
            ],
            [
                Digits::class,
                'ValidatorManager',
            ],
            [
                ToInt::class,
                'FilterManager',
            ],
            [
                InputFilter::class,
                'InputFilterManager',
            ],
            [
                Textarea::class,
                'FormElementManager',
            ],
        ];
    }
}
