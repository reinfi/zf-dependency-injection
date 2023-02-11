<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Http\Response;
use Laminas\Stdlib\ResponseInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResponseResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ResponseResolverTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsInjectionInterfaceForResponseInterface(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(ResponseInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsInjectionInterfaceForResponseClass(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Response::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsNullIfNoResponse(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Service1::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    public function testItReturnsNullIfClassDoesNotExists(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn('ServiceWhichDoesNotExists');
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    public function testItReturnsNullIfParameterHasNoClass(): void
    {
        $resolver = new ResponseResolver();

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }
}
