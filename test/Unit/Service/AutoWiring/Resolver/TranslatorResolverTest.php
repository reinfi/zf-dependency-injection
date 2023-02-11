<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class TranslatorResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider containerHasCallsProvider
     */
    public function testItReturnsInjectionInterfaceForTranslatorInterface(
        array $containerHasCalls
    ): void {
        $container = $this->prophesize(ContainerInterface::class);

        foreach ($containerHasCalls as $serviceName => $result) {
            $container->addMethodProphecy(
                (new MethodProphecy(
                    $container,
                    'has',
                    [Argument::exact($serviceName)]
                ))
                    ->willReturn($result)
                    ->shouldBeCalled()
            );
        }

        $resolver = new TranslatorResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(TranslatorInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @dataProvider containerHasCallsProvider
     */
    public function testItReturnsInjectionInterfaceForTranslatorClass(
        array $containerHasCalls
    ): void {
        $container = $this->prophesize(ContainerInterface::class);

        foreach ($containerHasCalls as $serviceName => $result) {
            $container->addMethodProphecy(
                (new MethodProphecy(
                    $container,
                    'has',
                    [Argument::exact($serviceName)]
                ))
                    ->willReturn($result)
            );
        }
        $resolver = new TranslatorResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Translator::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsNullIfNoTranslatorRegistered(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->has('MvcTranslator')->willReturn(false);
        $container->has(TranslatorInterface::class)->willReturn(false);
        $container->has('Translator')->willReturn(false);

        $resolver = new TranslatorResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn(Translator::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    public function testItReturnsNullIfReflectionParameterHasNoType(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->has(TranslatorInterface::class)->willReturn(
            false
        )->shouldNotBeCalled();

        $resolver = new TranslatorResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null parameter has no class'
        );
    }

    public function testItReturnsNullIfNoTranslatorInterface(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new TranslatorResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn('');
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new TranslatorResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        self::assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    public function containerHasCallsProvider(): array
    {
        return [
            [
                [
                    'MvcTranslator' => true,
                ],
            ],
            [
                [
                    'MvcTranslator' => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => true,
                ],
            ],
            [
                [
                    'MvcTranslator' => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => false,
                    'Translator' => true,
                ],
            ],
        ];
    }
}
