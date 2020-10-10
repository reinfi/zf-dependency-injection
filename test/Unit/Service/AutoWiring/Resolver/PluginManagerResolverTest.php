<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Test\Service\Service1;
use ReflectionClass;
use ReflectionParameter;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class PluginManagerResolverTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    /**
     * @test
     *
     * @dataProvider getPluginManagerData
     *
     * @param string $serviceClass
     * @param string $interfaceClass
     * @param string $pluginManager
     */
    public function itReturnsInjectionInterfaceForPluginManager(
        string $serviceClass,
        string $interfaceClass,
        string $pluginManager
    ) {
        $pluginManagerClass = $this->prophesize(ContainerInterface::class);
        $pluginManagerClass->has($serviceClass)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($pluginManager)
            ->willReturn($pluginManagerClass->reveal());

        $resolver = new PluginManagerResolver($container->reveal());

        $class = $this->prophesize(ReflectionClass::class);
        $class->getName()->willReturn($serviceClass);
        $class->getInterfaceNames()->willReturn([$interfaceClass]);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiringPluginManager::class, $injection);
    }


    /**
     * @test
     *
     * @dataProvider getPluginManagerData
     *
     * @param string $serviceClass
     * @param string $interfaceClass
     * @param string $pluginManager
     */
    public function itReturnsServiceAndPluginManager(
        string $serviceClass,
        string $interfaceClass,
        string $pluginManager
    ) {
        $pluginManagerClass = $this->prophesize(ContainerInterface::class);
        $pluginManagerClass->has($serviceClass)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($pluginManager)
            ->willReturn($pluginManagerClass->reveal());

        $resolver = new PluginManagerResolver($container->reveal());

        $class = $this->prophesize(ReflectionClass::class);
        $class->getName()->willReturn($serviceClass);
        $class->getInterfaceNames()->willReturn([$interfaceClass]);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $reflCass = new ReflectionClass($injection);
        $property = $reflCass->getProperty('serviceName');
        $property->setAccessible(true);

        $this->assertEquals(
            $serviceClass,
            $property->getValue($injection)
        );

        $property = $reflCass->getProperty('pluginManager');
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
            InjectionInterface::class,
            'InjectionManager'
        );

        $pluginManagerClass = $this->prophesize(ContainerInterface::class);
        $pluginManagerClass->has(Service1::class)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('InjectionManager')
            ->willReturn($pluginManagerClass->reveal());
        $resolver = new PluginManagerResolver($container->reveal());

        $class = $this->prophesize(ReflectionClass::class);
        $class->getName()->willReturn(Service1::class);
        $class->getInterfaceNames()->willReturn([InjectionInterface::class]);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

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

        $class = $this->prophesize(ReflectionClass::class);
        $class->getName()->willReturn(Service1::class);
        $class->getInterfaceNames()->willReturn(['UnknowInterface']);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoClass()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new PluginManagerResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

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
                ExtractorInterface::class,
                'Laminas\Hydrator\HydratorInterface',
                'HydratorManager',
            ],
            [
                ExtractorInterface::class,
                'Laminas\View\Helper\HelperInterface',
                'ViewHelperManager',
            ],
            [
                ExtractorInterface::class,
                'Laminas\Validator\ValidatorInterface',
                'ValidatorManager',
            ],
            [
                ExtractorInterface::class,
                'Laminas\Filter\FilterInterface',
                'FilterManager',
            ],
            [
                ExtractorInterface::class,
                'Laminas\InputFilter\InputFilterInterface',
                'InputFilterManager',
            ],
            [
                ExtractorInterface::class,
                'Laminas\InputFilter\InputInterface',
                'InputFilterManager',
            ],
            [
                ExtractorInterface::class,
                'Laminas\Form\ElementInterface',
                'FormElementManager',
            ],
        ];
    }
}
