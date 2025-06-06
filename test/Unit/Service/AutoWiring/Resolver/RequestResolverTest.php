<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Http\Request;
use Laminas\Stdlib\RequestInterface;
use PHPUnit\Framework\TestCase;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
final class RequestResolverTest extends TestCase
{
    public function testItReturnsInjectionInterfaceForRequestInterface(): void
    {
        $requestResolver = new RequestResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(RequestInterface::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $requestResolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsInjectionInterfaceForRequestClass(): void
    {
        $requestResolver = new RequestResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Request::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $requestResolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsNullIfNoRequest(): void
    {
        $requestResolver = new RequestResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($requestResolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfClassDoesNotExists(): void
    {
        $requestResolver = new RequestResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn('ServiceWhichDoesNotExists');
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($requestResolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfParameterHasNoClass(): void
    {
        $requestResolver = new RequestResolver();

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        self::assertNull($requestResolver->resolve($parameter), 'return value should be null if not found');
    }
}
