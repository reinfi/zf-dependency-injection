<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\Factory\AnnotationExtractorFactory;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory
 */
final class AnnotationExtractorFactoryTest extends TestCase
{
    public function testItReturnsAnnotationExtractor(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $annotationExtractorFactory = new AnnotationExtractorFactory();

        self::assertInstanceOf(AnnotationExtractor::class, $annotationExtractorFactory($container));
    }
}
