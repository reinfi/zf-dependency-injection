<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\BuildInTypeWithDefaultResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
final class BuildInTypeWithDefaultResolverTest extends TestCase
{
    public function testItReturnsInjectionInterface(): void
    {
        $buildInTypeWithDefaultResolver = new BuildInTypeWithDefaultResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('isBuiltin')->willReturn(true);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);
        $parameter->method('isDefaultValueAvailable')->willReturn(true);
        $parameter->expects($this->once())
            ->method('getDefaultValue')
            ->willReturn(0);

        $injection = $buildInTypeWithDefaultResolver->resolve($parameter);

        self::assertInstanceOf(InjectionInterface::class, $injection);
    }

    public function testItReturnsNullIfNoType(): void
    {
        $buildInTypeWithDefaultResolver = new BuildInTypeWithDefaultResolver();

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        $injection = $buildInTypeWithDefaultResolver->resolve($parameter);

        self::assertNull($injection, 'Should be null if parameter has no type');
    }

    public function testItReturnsNullIfNoBuildInType(): void
    {
        $buildInTypeWithDefaultResolver = new BuildInTypeWithDefaultResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('isBuiltin')->willReturn(false);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('hasType')->willReturn(true);
        $parameter->method('getType')->willReturn($type);

        $injection = $buildInTypeWithDefaultResolver->resolve($parameter);

        self::assertNull($injection, 'Should be null if parameter is not a buildin type');
    }

    public function testItReturnsNullIfNoDefaultValueAvailable(): void
    {
        $buildInTypeWithDefaultResolver = new BuildInTypeWithDefaultResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('isBuiltin')->willReturn(true);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);
        $parameter->method('isDefaultValueAvailable')->willReturn(false);

        $injection = $buildInTypeWithDefaultResolver->resolve($parameter);

        self::assertNull($injection, 'Should be null if parameter is not a buildin type');
    }
}
