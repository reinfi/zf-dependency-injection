<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use Laminas\Config\Config;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\AttributeExtractor;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorChain;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\Extractor\Factory\ExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory
 */
class ExtractorFactoryTest extends TestCase
{
    public function testItReturnsExtractorDefinedInConfig(): void
    {
        $moduleConfig = new Config([
            'extractor' => YamlExtractor::class,
        ]);

        $yamlExtractor = $this->createMock(YamlExtractor::class);
        $annotationExtractor = $this->createMock(AnnotationExtractor::class);
        $attributeExtractor = $this->createMock(AttributeExtractor::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')
            ->willReturnCallback(function ($service) use (
                $moduleConfig,
                $yamlExtractor,
                $annotationExtractor,
                $attributeExtractor
            ) {
                return match ($service) {
                    ModuleConfig::class => $moduleConfig->toArray(),
                    YamlExtractor::class => $yamlExtractor,
                    AnnotationExtractor::class => $annotationExtractor,
                    AttributeExtractor::class => $attributeExtractor,
                    default => null,
                };
            });

        $factory = new ExtractorFactory();

        $extractor = $factory($container);

        self::assertInstanceOf(ExtractorChain::class, $extractor);

        $reflectionClass = new ReflectionClass($extractor);
        $chainProperty = $reflectionClass->getProperty('chain');
        $chainProperty->setAccessible(true);

        $chain = $chainProperty->getValue($extractor);

        self::assertTrue(is_array($chain));

        self::assertCount(3, $chain);

        self::assertContainsOnlyInstancesOf(ExtractorInterface::class, $chain);
    }

    public function testItReturnsArrayOfExtractorsDefinedInConfig(): void
    {
        $moduleConfig = new Config([
            'extractor' => [YamlExtractor::class],
        ]);

        $yamlExtractor = $this->createMock(YamlExtractor::class);
        $annotationExtractor = $this->createMock(AnnotationExtractor::class);
        $attributeExtractor = $this->createMock(AttributeExtractor::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')
            ->willReturnCallback(function ($service) use (
                $moduleConfig,
                $yamlExtractor,
                $annotationExtractor,
                $attributeExtractor
            ) {
                return match ($service) {
                    ModuleConfig::class => $moduleConfig->toArray(),
                    YamlExtractor::class => $yamlExtractor,
                    AnnotationExtractor::class => $annotationExtractor,
                    AttributeExtractor::class => $attributeExtractor,
                    default => null,
                };
            });

        $factory = new ExtractorFactory();

        $extractor = $factory($container);

        self::assertInstanceOf(ExtractorChain::class, $extractor);

        $reflectionClass = new ReflectionClass($extractor);
        $chainProperty = $reflectionClass->getProperty('chain');
        $chainProperty->setAccessible(true);

        $chain = $chainProperty->getValue($extractor);

        self::assertTrue(is_array($chain));

        self::assertCount(3, $chain);

        self::assertContainsOnlyInstancesOf(ExtractorInterface::class, $chain);
    }

    public function testItReturnsAnnotationExtractorIfNoneDefined(): void
    {
        $moduleConfig = new Config([]);

        $annotationExtractor = $this->createMock(AnnotationExtractor::class);
        $attributeExtractor = $this->createMock(AttributeExtractor::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->method('get')
            ->willReturnCallback(function ($service) use ($moduleConfig, $annotationExtractor, $attributeExtractor) {
                return match ($service) {
                    ModuleConfig::class => $moduleConfig->toArray(),
                    AnnotationExtractor::class => $annotationExtractor,
                    AttributeExtractor::class => $attributeExtractor,
                    default => null,
                };
            });

        $factory = new ExtractorFactory();

        $extractor = $factory($container);

        self::assertInstanceOf(ExtractorChain::class, $extractor);

        $reflectionClass = new ReflectionClass($extractor);
        $chainProperty = $reflectionClass->getProperty('chain');
        $chainProperty->setAccessible(true);

        $chain = $chainProperty->getValue($extractor);

        self::assertTrue(is_array($chain));

        self::assertCount(2, $chain);

        self::assertInstanceOf(AnnotationExtractor::class, $chain[0]);

        self::assertContainsOnlyInstancesOf(ExtractorInterface::class, $chain);
    }
}
