<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceIfIsInterfaceTypeHint()
    {
        $resolver = new ContainerInterfaceResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(ContainerInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceIfHasInterfaceImplemented()
    {
        $resolver = new ContainerInterfaceResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(ServiceLocatorInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfIsAbstractPluginManager()
    {
        $resolver = new ContainerInterfaceResolver();
        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(AbstractPluginManager::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfOtherClass()
    {
        $resolver = new ContainerInterfaceResolver();

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
        $resolver = new ContainerInterfaceResolver();

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
        $resolver = new ContainerInterfaceResolver();

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }
}
