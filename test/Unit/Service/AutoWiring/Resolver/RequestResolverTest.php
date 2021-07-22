<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Http\Request;
use Laminas\Stdlib\RequestInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class RequestResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestInterface()
    {
        $resolver = new RequestResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(RequestInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestClass()
    {
        $resolver = new RequestResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Request::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoRequest()
    {
        $resolver = new RequestResolver();

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
    public function itReturnsNullIfClassDoesNotExists()
    {
        $resolver = new RequestResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn('ServiceWhichDoesNotExists');
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
    public function itReturnsNullIfParameterHasNoClass()
    {
        $resolver = new RequestResolver();

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }
}
