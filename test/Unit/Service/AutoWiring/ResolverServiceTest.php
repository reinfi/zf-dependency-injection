<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;
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
    public function testItResolvesConstructorArguments(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->method('resolve')
            ->with($this->isInstanceOf(ReflectionParameter::class))
            ->willReturn(new AutoWiring(Service2::class));

        $service = new ResolverService([$resolver]);

        $injections = $service->resolve(Service1::class);

        self::assertCount(3, $injections);
        self::assertContainsOnlyInstancesOf(InjectionInterface::class, $injections);
    }

    public function testItResolvesConstructorArgumentsWithOptionsParameter(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->method('resolve')
            ->with($this->isInstanceOf(ReflectionParameter::class))
            ->willReturn(new AutoWiring(Service2::class));

        $service = new ResolverService([$resolver]);

        $container = $this->createMock(ContainerInterface::class);
        $injections = $service->resolve(Service1::class, [
            'foo' => 'bar',
        ]);

        self::assertCount(3, $injections);
        self::assertContainsOnlyInstancesOf(InjectionInterface::class, $injections);
        self::assertSame('bar', $injections[2]($container));
    }

    public function testItReturnsEmptyArrayIfNoConstructorArguments(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);

        $service = new ResolverService([$resolver]);

        $injections = $service->resolve(Service2::class);

        self::assertCount(0, $injections);
    }

    #[DataProvider('exceptionServiceDataProvider')]
    public function testItThrowsExceptionIfServiceCannotResolved(string $serviceClass): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->method('resolve')
            ->with($this->isInstanceOf(ReflectionParameter::class))
            ->willReturn(null);

        $service = new ResolverService([$resolver]);

        $service->resolve($serviceClass);
    }

    #[DataProvider('exceptionServiceDataProvider')]
    public function testItThrowsExceptionIfServiceCannotBeResolved(string $serviceClass): void
    {
        $this->expectExceptionMessage($serviceClass);

        $resolver = $this->createMock(ResolverInterface::class);
        $resolver->method('resolve')
            ->with($this->isInstanceOf(ReflectionParameter::class))
            ->willReturn(null);

        $service = new ResolverService([$resolver]);

        $service->resolve($serviceClass);
    }

    public static function exceptionServiceDataProvider(): array
    {
        return [[Service1::class], [ServiceNoTypeHint::class], [ServiceBuildInType::class]];
    }
}
