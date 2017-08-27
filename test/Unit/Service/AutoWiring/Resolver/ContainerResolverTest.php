<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver
 */
class ContainerResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsInjectionInterface()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $resolver = new ContainerResolver($container->reveal());

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsClassName()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $resolver = new ContainerResolver($container->reveal());

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $reflCass = new \ReflectionClass($injection);
        $property = $reflCass->getProperty('serviceName');
        $property->setAccessible(true);

        $this->assertEquals(
            Service1::class,
            $property->getValue($injection)
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfServiceNotFound()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(false);

        $resolver = new ContainerResolver($container->reveal());

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoClass()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new ContainerResolver($container->reveal());

        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }
}