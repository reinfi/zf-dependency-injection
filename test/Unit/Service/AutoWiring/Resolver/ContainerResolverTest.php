<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ContainerResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterface()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $resolver = new ContainerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

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

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $reflCass = new ReflectionClass($injection);
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

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfClassDoesNotExists()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(false);

        $resolver = new ContainerResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn('ServiceWhichDoesNotExists');
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoType()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new ContainerResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }
}
