<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\BuildInTypeWithDefaultResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class BuildInTypeWithDefaultResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterface(): void
    {
        $resolver = new BuildInTypeWithDefaultResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->isBuiltin()->willReturn(true);

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());
        $parameter->isDefaultValueAvailable()->willReturn(true);
        $parameter->getDefaultValue()->willReturn(0)->shouldBeCalled();

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoType(): void
    {
        $resolver = new BuildInTypeWithDefaultResolver();

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection, 'Should be null if parameter has no type');
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoBuildInType(): void
    {
        $resolver = new BuildInTypeWithDefaultResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->isBuiltin()->willReturn(false);

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->hasType()->willReturn(true);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection, 'Should be null if parameter is not a buildin type');
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoDefaultValueAvailable(): void
    {
        $resolver = new BuildInTypeWithDefaultResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->isBuiltin()->willReturn(true);

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());
        $parameter->isDefaultValueAvailable()->willReturn(false);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection, 'Should be null if parameter is not a buildin type');
    }
}
