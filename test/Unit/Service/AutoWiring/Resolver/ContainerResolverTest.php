<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
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
final class ContainerResolverTest extends TestCase
{
    public function testItReturnsInjectionInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(Service1::class)
            ->willReturn(true);

        $containerResolver = new ContainerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $containerResolver->resolve($parameter);

        self::assertInstanceOf(InjectionInterface::class, $injection);
    }

    public function testItReturnsClassName(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(Service1::class)
            ->willReturn(true);

        $containerResolver = new ContainerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $containerResolver->resolve($parameter);

        $reflectionClass = new ReflectionClass($injection);
        $reflectionProperty = $reflectionClass->getProperty('serviceName');
        $reflectionProperty->setAccessible(true);

        self::assertEquals(Service1::class, $reflectionProperty->getValue($injection));
    }

    public function testItReturnsNullIfServiceNotFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(Service1::class)
            ->willReturn(false);

        $containerResolver = new ContainerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $containerResolver->resolve($parameter);

        self::assertNull($injection);
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $containerResolver = new ContainerResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        $injection = $containerResolver->resolve($parameter);

        self::assertNull($injection);
    }
}
