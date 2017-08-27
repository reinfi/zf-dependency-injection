<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Injection\AutoWiringEntityRepository;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\DoctrineRepositoryResolver;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver
 */
class DoctrineRepositoryResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForEntityRepositoryParentClass()
    {
        $resolver = new DoctrineRepositoryResolver();

        $parentClass = $this->prophesize(\ReflectionClass::class);
        $parentClass->getName()->willReturn(EntityRepository::class);

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getParentClass()->willReturn($parentClass->reveal());
        $class->isInterface()->willReturn(false);
        $class->getName()->willReturn('MyEntity');
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiringEntityRepository::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForEntityRepositoryInterface()
    {
        $resolver = new DoctrineRepositoryResolver();

        $parentClass = $this->prophesize(\ReflectionClass::class);
        $parentClass->getName()->willReturn('EntityRepositoryFakeParentClass');

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getParentClass()->willReturn($parentClass->reveal());
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([ObjectRepository::class]);
        $class->getName()->willReturn('MyEntity');
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiringEntityRepository::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfParentClassIsnull()
    {
        $resolver = new DoctrineRepositoryResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getParentClass()->willReturn(null);
        $class->isInterface()->willReturn(false);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfInterfaceIsNotImplemented()
    {
        $resolver = new DoctrineRepositoryResolver();

        $parentClass = $this->prophesize(\ReflectionClass::class);
        $parentClass->getName()->willReturn('EntityRepositoryFakeParentClass');

        $class = $this->prophesize(\ReflectionClass::class);
        $class->getParentClass()->willReturn($parentClass->reveal());
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([]);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoClass()
    {
        $resolver = new DoctrineRepositoryResolver();

        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }
}