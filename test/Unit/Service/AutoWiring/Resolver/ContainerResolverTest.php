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
class ContainerResolverTest extends TestCase
{
    public function testItReturnsInjectionInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(Service1::class)
            ->willReturn(true);

        $resolver = new ContainerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(InjectionInterface::class, $injection);
    }

    public function testItReturnsClassName(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(Service1::class)
            ->willReturn(true);

        $resolver = new ContainerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        $reflCass = new ReflectionClass($injection);
        $property = $reflCass->getProperty('serviceName');
        $property->setAccessible(true);

        self::assertEquals(Service1::class, $property->getValue($injection));
    }

    public function testItReturnsNullIfServiceNotFound(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('has')
            ->with(Service1::class)
            ->willReturn(false);

        $resolver = new ContainerResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $resolver = new ContainerResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }
}
