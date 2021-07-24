<?php

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
     * @test
     *
     * @dataProvider getPluginManagerData
     *
     * @param string $serviceClass
     * @param string $pluginManager
     */
    public function itReturnsInjectionInterfaceForPluginManager(
        string $serviceClass,
        string $pluginManager
    ) {
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

        $this->assertInstanceOf(AutoWiringPluginManager::class, $injection);
    }

    /**
     * @test
     *
     * @dataProvider getPluginManagerData
     *
     * @param string $serviceClass
     * @param string $pluginManager
     */
    public function itReturnsServiceAndPluginManager(
        string $serviceClass,
        string $pluginManager
    ) {
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

        $this->assertNotNull(
            $injection,
            'injection could not resolved'
        );

        $reflectionClass = new ReflectionClass($injection);
        $property = $reflectionClass->getProperty('serviceName');
        $property->setAccessible(true);

        $this->assertEquals(
            $serviceClass,
            $property->getValue($injection)
        );

        $property = $reflectionClass->getProperty('pluginManager');
        $property->setAccessible(true);

        $this->assertEquals(
            $pluginManager,
            $property->getValue($injection)
        );
    }

    /**
     * @test
     */
    public function itResolvesAdditionalInterfaceMappings()
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

        $this->assertNotNull(
            $injection,
            'injection could not resolved'
        );

        $reflCass = new ReflectionClass($injection);
        $property = $reflCass->getProperty('pluginManager');
        $property->setAccessible(true);

        $this->assertEquals(
            'InjectionManager',
            $property->getValue($injection)
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoPluginManagerFound()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $resolver = new PluginManagerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoType()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new PluginManagerResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @return array
     */
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
