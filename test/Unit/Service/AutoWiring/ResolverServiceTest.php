<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInType;
use Reinfi\DependencyInjection\Test\Service\ServiceNoTypeHint;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring
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

        $service = new ResolverService([ $resolver->reveal() ]);

        $injections = $service->resolve(Service1::class);

        $this->assertCount(3, $injections);
        $this->assertContainsOnlyInstancesOf(
            InjectionInterface::class, $injections
        );
    }

    /**
     * @test
     */
    public function itResolvesConstructorArgumentsWithOptionsParameter()
    {
        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve(Argument::type(\ReflectionParameter::class))
            ->willReturn(
                new AutoWiring(Service2::class)
            );

        $service = new ResolverService([ $resolver->reveal() ]);

        $injections = $service->resolve(Service1::class, ['foo' => 'bar']);

        $this->assertCount(3, $injections);
        $this->assertContainsOnlyInstancesOf(
            InjectionInterface::class, $injections
        );
        $this->assertSame(
            'bar',
            $injections[2]($this->prophesize(ContainerInterface::class)->reveal())
        );
    }

    /**
     * @test
     */
    public function itReturnsEmptyArrayIfNoConstructorArguments()
    {
        $resolver = $this->prophesize(ResolverInterface::class);

        $service = new ResolverService([ $resolver->reveal() ]);

        $injections = $service->resolve(Service2::class);

        $this->assertCount(0, $injections);
    }

    /**
     * @test
     *
     * @dataProvider exceptionServiceDataProvider
     *
     * @param string $serviceName
     */
    public function itThrowsExceptionIfDependencyCouldNotResolved(string $serviceName)
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve(Argument::type(\ReflectionParameter::class))
            ->willReturn(null);

        $service = new ResolverService([ $resolver->reveal() ]);

        $service->resolve($serviceName);
    }

    /**
     * @test
     *
     * @dataProvider exceptionServiceDataProvider
     *
     * @param string $serviceName
     */
    public function itShouldAddTheResolveClassToExceptionIfDependencyCouldNotResolved(string $serviceName)
    {
        $this->expectExceptionMessage($serviceName);

        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve(Argument::type(\ReflectionParameter::class))
                 ->willReturn(null);

        $service = new ResolverService([ $resolver->reveal() ]);

        $service->resolve($serviceName);
    }

    /**
     * @return array
     */
    public function exceptionServiceDataProvider()
    {
        return [
            [ Service1::class ],
            [ ServiceNoTypeHint::class ],
            [ ServiceBuildInType::class ],
        ];
    }
}
