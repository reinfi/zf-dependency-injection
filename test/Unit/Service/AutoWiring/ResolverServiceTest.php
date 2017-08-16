<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\Service1;
use Reinfi\DependencyInjection\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring
 */
class ResolverServiceTest extends TestCase
{
    /**
     * @test
     */
    public function itResolvesConstructorArguments()
    {
        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve(Argument::type(\ReflectionParameter::class))
            ->willReturn(
                new AutoWiring(Service2::class)
            );

        $service = new ResolverService([$resolver->reveal()]);

        $injections = $service->resolve(Service1::class);

        $this->assertCount(2, $injections);
        $this->assertContainsOnlyInstancesOf(InjectionInterface::class, $injections);
    }

    /**
     * @test
     */
    public function itReturnsEmptyArrayIfNoConstructorArguments()
    {
        $resolver = $this->prophesize(ResolverInterface::class);

        $service = new ResolverService([$resolver->reveal()]);

        $injections = $service->resolve(Service2::class);

        $this->assertCount(0, $injections);
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfDependencyCouldNotResolved()
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve(Argument::type(\ReflectionParameter::class))
            ->willReturn(null);

        $service = new ResolverService([$resolver->reveal()]);

        $service->resolve(Service1::class);
    }
}