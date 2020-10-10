<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use ReflectionClass;
use ReflectionParameter;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class TranslatorResolverTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    /**
     * @test
     * @dataProvider containerHasCallsProvider
     *
     * @param array $containerHasCalls
     */
    public function itReturnsInjectionInterfaceForTranslatorInterface(
        array $containerHasCalls
    ) {
        $container = $this->prophesize(ContainerInterface::class);

        foreach ($containerHasCalls as $serviceName => $result) {
            $container->addMethodProphecy(
                (new MethodProphecy($container, 'has', [ Argument::exact($serviceName) ]))
                ->willReturn($result)
                ->shouldBeCalled()
            );
        }

        $resolver = new TranslatorResolver($container->reveal());

        $class = new ReflectionClass(TranslatorInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class);

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
    ) {
        $container = $this->prophesize(ContainerInterface::class);

        foreach ($containerHasCalls as $serviceName => $result) {
            $container->addMethodProphecy(
                (new MethodProphecy($container, 'has', [ Argument::exact($serviceName) ]))
                    ->willReturn($result)
            );
        }
        $resolver = new TranslatorResolver($container->reveal());

        $class = new ReflectionClass(Translator::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoTranslatorRegistered()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->has('MvcTranslator')->willReturn(false);
        $container->has(TranslatorInterface::class)->willReturn(false);
        $container->has('Translator')->willReturn(false);

        $resolver = new TranslatorResolver($container->reveal());

        $class = new ReflectionClass(Translator::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfReflectionParameterHasNoClass()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $container->has(TranslatorInterface::class)->willReturn(false)->shouldNotBeCalled();

        $resolver = new TranslatorResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null parameter has no class'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoTranslatorInterface()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new TranslatorResolver($container->reveal());

        $class = $this->prophesize(ReflectionClass::class);
        $class->getName()->willReturn('');
        $class->getInterfaceNames()->willReturn([]);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $this->assertNull(
            $resolver->resolve($parameter->reveal()),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoClass()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $resolver = new TranslatorResolver($container->reveal());

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

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
                    'MvcTranslator'                            => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => true,
                ],
            ],
            [
                [
                    'MvcTranslator'                            => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => false,
                    'Translator'                               => true,
                ],
            ],
        ];
    }
}
