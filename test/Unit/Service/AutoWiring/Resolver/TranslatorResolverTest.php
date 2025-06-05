<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Iterator;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
final class TranslatorResolverTest extends TestCase
{
    #[DataProvider('containerHasCallsProvider')]
    public function testItReturnsInjectionInterfaceForTranslatorInterface(array $containerHasCalls): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(count($containerHasCalls)))
            ->method('has')
            ->willReturnCallback(fn ($serviceName) => $containerHasCalls[$serviceName] ?? false);

        $translatorResolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(TranslatorInterface::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $translatorResolver->resolve($parameter);

        self::assertInstanceOf(AutoWiring::class, $injection);
    }

    #[DataProvider('containerHasCallsProvider')]
    public function testItReturnsInjectionInterfaceForTranslatorClass(array $containerHasCalls): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(count($containerHasCalls)))
            ->method('has')
            ->willReturnCallback(fn ($serviceName) => $containerHasCalls[$serviceName] ?? false);

        $translatorResolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Translator::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        $injection = $translatorResolver->resolve($parameter);

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

        $translatorResolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn(Translator::class);
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($translatorResolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfReflectionParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->never())
            ->method('has');

        $translatorResolver = new TranslatorResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        self::assertNull(
            $translatorResolver->resolve($parameter),
            'return value should be null parameter has no class'
        );
    }

    public function testItReturnsNullIfNoTranslatorInterface(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $translatorResolver = new TranslatorResolver($container);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('getName')->willReturn('');
        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn($type);

        self::assertNull($translatorResolver->resolve($parameter), 'return value should be null if not found');
    }

    public function testItReturnsNullIfParameterHasNoType(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $translatorResolver = new TranslatorResolver($container);

        $parameter = $this->createMock(ReflectionParameter::class);
        $parameter->method('getType')->willReturn(null);

        self::assertNull($translatorResolver->resolve($parameter), 'return value should be null if not found');
    }

    public static function containerHasCallsProvider(): Iterator
    {
        yield [
            [
                'Laminas\Translator\TranslatorInterface' => true,
            ],
        ];
        yield [
            [
                'Laminas\Translator\TranslatorInterface' => false,
                'MvcTranslator' => true,
            ],
        ];
        yield [
            [
                'Laminas\Translator\TranslatorInterface' => false,
                'MvcTranslator' => false,
                'Laminas\I18n\Translator\TranslatorInterface' => true,
            ],
        ];
        yield [
            [
                'Laminas\Translator\TranslatorInterface' => false,
                'MvcTranslator' => false,
                'Laminas\I18n\Translator\TranslatorInterface' => false,
                'Translator' => true,
            ],
        ];
    }
}
