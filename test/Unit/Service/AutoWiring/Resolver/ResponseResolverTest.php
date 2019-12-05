<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResponseResolver;
use Zend\Http\Response;
use Zend\Stdlib\ResponseInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ResponseResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForResponseInterface()
    {
        $resolver = new ResponseResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getName()->willReturn(Response::class);
        $class->getInterfaceNames()->willReturn([ ResponseInterface::class ]);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForResponseClass()
    {
        $resolver = new ResponseResolver();

        $class = new \ReflectionClass(Response::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForResponseInterfaceAsTypeHint()
    {
        $resolver = new ResponseResolver();

        $class = new \ReflectionClass(ResponseInterface::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoResponse()
    {
        $resolver = new ResponseResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getName()->willReturn('');
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
        $resolver = new ResponseResolver();

        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }
}
