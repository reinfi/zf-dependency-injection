<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Http\Response;
use Laminas\Stdlib\ResponseInterface;
use PHPUnit\Framework\TestCase;
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
    public function testItReturnsInjectionInterfaceForResponseInterface(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(ResponseInterface::class);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsInjectionInterfaceForResponseClass(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Response::class);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsNullIfNoResponse(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfClassDoesNotExists(): void
    {
        $resolver = new ResponseResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn('ServiceWhichDoesNotExists');

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfParameterHasNoClass(): void
    {
        $resolver = new ResponseResolver();

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }
}
