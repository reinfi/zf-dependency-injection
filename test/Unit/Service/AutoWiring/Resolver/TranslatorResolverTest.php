<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use PHPUnit\Framework\TestCase;
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
    /**
     * @dataProvider containerHasCallsProvider
     */
    public function testItReturnsInjectionInterfaceForTranslatorInterface(array $containerHasCalls): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(count($containerHasCalls)))
            ->method('has')
            ->willReturnCallback(function ($serviceName) use ($containerHasCalls) {
                return $containerHasCalls[$serviceName] ?? false;
            });

        $resolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(TranslatorInterface::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @dataProvider containerHasCallsProvider
     */
    public function testItReturnsInjectionInterfaceForTranslatorClass(array $containerHasCalls): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(count($containerHasCalls)))
            ->method('has')
            ->willReturnCallback(function ($serviceName) use ($containerHasCalls) {
                return $containerHasCalls[$serviceName] ?? false;
            });

        $resolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Translator::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $resolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    public function testItReturnsNullIfNoTranslatorRegistered(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(4))
            ->method('has')
            ->willReturnMap([
                ['MvcTranslator', false],
                [TranslatorInterface::class, false],
                ['Laminas\Translator\TranslatorInterface', false],
                ['Translator', false],
            ]);

        $resolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Translator::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfReflectionParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->never())
            ->method('has');

        $resolver = new TranslatorResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        self::assertNull($resolver->resolve($parameter), 'return value should be null parameter has no class');
    }

    public function testItReturnsNullIfNoTranslatorInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $resolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn('');
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $resolver = new TranslatorResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        self::assertNull($resolver->resolve($parameter), 'return value should be null if not found');
    }

    public static function containerHasCallsProvider(): array
    {
        return [
            [
                [
                    'Laminas\Translator\TranslatorInterface' => true,
                ],
            ],
            [
                [
                    'Laminas\Translator\TranslatorInterface' => false,
                    'MvcTranslator' => true,
                ],
            ],
            [
                [
                    'Laminas\Translator\TranslatorInterface' => false,
                    'MvcTranslator' => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => true,
                ],
            ],
            [
                [
                    'Laminas\Translator\TranslatorInterface' => false,
                    'MvcTranslator' => false,
                    'Laminas\I18n\Translator\TranslatorInterface' => false,
                    'Translator' => true,
                ],
            ],
        ];
    }
}
