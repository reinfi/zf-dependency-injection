<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionProperty;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor
 */
final class AnnotationExtractorTest extends TestCase
{
    public function testItResolvesPropertyAnnotations(): void
    {
        $annotation = $this->createMock(AnnotationInterface::class);

        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->exactly(3))
            ->method('getPropertyAnnotation')
            ->with($this->isInstanceOf(ReflectionProperty::class), AnnotationInterface::class)
            ->willReturn($annotation);

        $annotationExtractor = new AnnotationExtractor($reader);

        $injections = $annotationExtractor->getPropertiesInjections(ServiceAnnotation::class);

        self::assertCount(3, $injections);
        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItResolvesConstructorAnnotations(): void
    {
        $annotation = $this->createMock(AnnotationInterface::class);

        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->once())
            ->method('getMethodAnnotations')
            ->with($this->isInstanceOf(ReflectionMethod::class))
            ->willReturn([$annotation, $annotation]);

        $annotationExtractor = new AnnotationExtractor($reader);

        $injections = $annotationExtractor->getConstructorInjections(ServiceAnnotation::class);

        self::assertCount(2, $injections);
        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItReturnsEmptyArrayIfNoPropertyAnnotationsFound(): void
    {
        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->exactly(3))
            ->method('getPropertyAnnotation')
            ->with($this->isInstanceOf(ReflectionProperty::class), AnnotationInterface::class)
            ->willReturn(null);

        $annotationExtractor = new AnnotationExtractor($reader);

        $injections = $annotationExtractor->getPropertiesInjections(ServiceAnnotation::class);

        self::assertCount(0, $injections);
    }

    public function testItReturnsEmptyArrayIfNoConstructorAnnotationsFound(): void
    {
        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->once())
            ->method('getMethodAnnotations')
            ->with($this->isInstanceOf(ReflectionMethod::class))
            ->willReturn([]);

        $annotationExtractor = new AnnotationExtractor($reader);

        $injections = $annotationExtractor->getConstructorInjections(ServiceAnnotation::class);

        self::assertCount(0, $injections);
    }
}
