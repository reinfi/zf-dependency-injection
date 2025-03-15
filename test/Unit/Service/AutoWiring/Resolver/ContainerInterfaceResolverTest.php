<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolverTest extends TestCase
{
    public function testItReturnsInjectionInterfaceIfIsInterfaceTypeHint(): void
    {
        $resolver = new ContainerInterfaceResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(ContainerInterface::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(InjectionInterface::class, $injection);
    }

    public function testItReturnsInjectionInterfaceIfHasInterfaceImplemented(): void
    {
        $resolver = new ContainerInterfaceResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(ServiceLocatorInterface::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(InjectionInterface::class, $injection);
    }

    public function testItReturnsNullIfIsAbstractPluginManager(): void
    {
        $resolver = new ContainerInterfaceResolver();
        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(AbstractPluginManager::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }

    public function testItReturnsNullIfOtherClass(): void
    {
        $resolver = new ContainerInterfaceResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Service1::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }

    public function testItReturnsNullIfClassDoesNotExists(): void
    {
        $resolver = new ContainerInterfaceResolver();

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn('ServiceWhichDoesNotExists');
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $resolver = new ContainerInterfaceResolver();

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        $injection = $resolver->resolve($parameter);

        self::assertNull($injection);
    }
}
