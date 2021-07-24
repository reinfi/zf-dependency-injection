<?php

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
     * @test
     * @dataProvider containerHasCallsProvider
     *
     * @param array $containerHasCalls
     */
    public function itReturnsInjectionInterfaceForTranslatorInterface(
        array $containerHasCalls
    ): void {
        $container = $this->prophesize(ContainerInterface::class);

        foreach ($containerHasCalls as $serviceName => $result) {
            $container->addMethodProphecy(
                (new MethodProphecy(
                    $container, 'has', [ Argument::exact($serviceName) ]
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

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     * @dataProvider containerHasCallsProvider
     *
     * @param array $containerHasCalls
     */
    public function itReturnsInjectionInterfaceForTranslatorClass(
        array $containerHasCalls
    ): void {
        $container = $this->prophesize(ContainerInterface::class);

        foreach ($containerHasCalls as $serviceName => $result) {
            $container->addMethodProphecy(
                (new MethodProphecy(
                    $container, 'has', [ Argument::exact($serviceName) ]
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

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoTranslatorRegistered(): void
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

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfReflectionParameterHasNoType(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->has(TranslatorInterface::class)->willReturn(
            false
        )->shouldNotBeCalled();

        $resolver = new TranslatorResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null parameter has no class'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoTranslatorInterface(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new TranslatorResolver($container->reveal());

        $type = $this->prophesize(ReflectionNamedType::class);
        $type->getName()->willReturn('');
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn($type->reveal());

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new TranslatorResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getType()->willReturn(null);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @return array
     */
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
                    'MvcTranslator'                               => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => true,
                ],
            ],
            [
                [
                    'MvcTranslator'                               => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => false,
                    'Translator'                                  => true,
                ],
            ],
        ];
    }
}
