<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
use Zend\Http\Request;
use Zend\Stdlib\RequestInterface;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver
 */
class RequestResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestInterface()
    {
        $resolver = new RequestResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getInterfaceNames()->willReturn([ RequestInterface::class ]);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestClass()
    {
        $resolver = new RequestResolver();

        $class = new \ReflectionClass(Request::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoRequest()
    {
        $resolver = new RequestResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getInterfaceNames()->willReturn([]);
        $parameter = $this->prophesize(\ReflectionParameter::class);
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
        $resolver = new RequestResolver();

        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }
}