<?php

namespace Reinfi\DependencyInjection\Unit\Service\Extractor\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\Factory\AnnotationExtractorFactory;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\Extractor\Factory
 */
class AnnotationExtractorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAnnotationExtractor()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $factory = new AnnotationExtractorFactory();

        $this->assertInstanceOf(
            AnnotationExtractor::class,
            $factory($container->reveal())
        );
    }
}