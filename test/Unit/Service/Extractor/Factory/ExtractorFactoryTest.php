<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use Laminas\Config\Config;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
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
    use ProphecyTrait;

    public function testItReturnsExtractorDefinedInConfig(): void
    {
        $moduleConfig = new Config([
            'extractor' => YamlExtractor::class,
        ]);

        $yamlExtractor = $this->prophesize(YamlExtractor::class);
        $annotationExtractor = $this->prophesize(AnnotationExtractor::class);
        $attributeExtractor = $this->prophesize(AttributeExtractor::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig->toArray())
            ->shouldBeCalled();
        $container->get(YamlExtractor::class)
            ->willReturn($yamlExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AnnotationExtractor::class)
            ->willReturn($annotationExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AttributeExtractor::class)
            ->willReturn($attributeExtractor->reveal())
            ->shouldBeCalled();

        $factory = new ExtractorFactory();

        $extractor = $factory($container->reveal());

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
            'extractor' => [
                YamlExtractor::class,
            ],
        ]);

        $yamlExtractor = $this->prophesize(YamlExtractor::class);
        $annotationExtractor = $this->prophesize(AnnotationExtractor::class);
        $attributeExtractor = $this->prophesize(AttributeExtractor::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig->toArray())
            ->shouldBeCalled();
        $container->get(YamlExtractor::class)
            ->willReturn($yamlExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AnnotationExtractor::class)
            ->willReturn($annotationExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AttributeExtractor::class)
            ->willReturn($attributeExtractor->reveal())
            ->shouldBeCalled();

        $factory = new ExtractorFactory();

        $extractor = $factory($container->reveal());

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
        $isPhp8OrAbove = version_compare(PHP_VERSION, '8.0.0') >= 0;

        $moduleConfig = new Config([
            'extractor' => [
                YamlExtractor::class,
            ],
        ]);

        $yamlExtractor = $this->prophesize(YamlExtractor::class);
        $annotationExtractor = $this->prophesize(AnnotationExtractor::class);
        $attributeExtractor = $this->prophesize(AttributeExtractor::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig->toArray())
            ->shouldBeCalled();
        $container->get(YamlExtractor::class)
            ->willReturn($yamlExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AnnotationExtractor::class)
            ->willReturn($annotationExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AttributeExtractor::class)
            ->willReturn($attributeExtractor->reveal())
            ->shouldBeCalledTimes($isPhp8OrAbove ? 1 : 0);

        $factory = new ExtractorFactory();

        $extractor = $factory($container->reveal());

        self::assertInstanceOf(ExtractorChain::class, $extractor);

        $reflectionClass = new ReflectionClass($extractor);
        $chainProperty = $reflectionClass->getProperty('chain');
        $chainProperty->setAccessible(true);

        $chain = $chainProperty->getValue($extractor);

        self::assertTrue(is_array($chain));

        if ($isPhp8OrAbove) {
            self::assertCount(3, $chain);
        } else {
            self::assertCount(2, $chain);
        }

        self::assertContainsOnlyInstancesOf(ExtractorInterface::class, $chain);
    }

    public function testItReturnsAnnotationExtractorIfNoneDefined(): void
    {
        $moduleConfig = new Config([]);

        $annotationExtractor = $this->prophesize(AnnotationExtractor::class);
        $attributeExtractor = $this->prophesize(AttributeExtractor::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig->toArray())
            ->shouldBeCalled();
        $container->get(AnnotationExtractor::class)
            ->willReturn($annotationExtractor->reveal())
            ->shouldBeCalled();
        $container->get(AttributeExtractor::class)
            ->willReturn($attributeExtractor->reveal())
            ->shouldBeCalled();

        $factory = new ExtractorFactory();

        $extractor = $factory($container->reveal());

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
