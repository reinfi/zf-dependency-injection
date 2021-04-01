<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use Laminas\Config\Config;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\Factory\ExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory
 */
class ExtractorFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsExtractorDefinedInConfig()
    {
        $moduleConfig = new Config(['extractor' => YamlExtractor::class]);

        $yamlExtractor = $this->prophesize(YamlExtractor::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig);
        $container->get(YamlExtractor::class)
            ->willReturn($yamlExtractor->reveal());

        $factory = new ExtractorFactory();

        $this->assertInstanceOf(
            YamlExtractor::class,
            $factory($container->reveal())
        );
    }

    /**
     * @test
     */
    public function itReturnsAnnotationExtractorIfNoneDefined()
    {
        $moduleConfig = new Config([]);

        $annotationExtractor = $this->prophesize(AnnotationExtractor::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig);
        $container->get(AnnotationExtractor::class)
            ->willReturn($annotationExtractor->reveal());

        $factory = new ExtractorFactory();

        $this->assertInstanceOf(
            AnnotationExtractor::class,
            $factory($container->reveal())
        );
    }
}
