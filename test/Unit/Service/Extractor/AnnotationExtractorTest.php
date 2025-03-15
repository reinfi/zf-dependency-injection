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
class AnnotationExtractorTest extends TestCase
{
    public function testItResolvesPropertyAnnotations(): void
    {
        $annotation = $this->createMock(AnnotationInterface::class);

        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->exactly(3))
            ->method('getPropertyAnnotation')
            ->with($this->isInstanceOf(ReflectionProperty::class), $this->equalTo(AnnotationInterface::class))
            ->willReturn($annotation);

        $extractor = new AnnotationExtractor($reader);

        $injections = $extractor->getPropertiesInjections(ServiceAnnotation::class);

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

        $extractor = new AnnotationExtractor($reader);

        $injections = $extractor->getConstructorInjections(ServiceAnnotation::class);

        self::assertCount(2, $injections);
        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItReturnsEmptyArrayIfNoPropertyAnnotationsFound(): void
    {
        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->exactly(3))
            ->method('getPropertyAnnotation')
            ->with($this->isInstanceOf(ReflectionProperty::class), $this->equalTo(AnnotationInterface::class))
            ->willReturn(null);

        $extractor = new AnnotationExtractor($reader);

        $injections = $extractor->getPropertiesInjections(ServiceAnnotation::class);

        self::assertCount(0, $injections);
    }

    public function testItReturnsEmptyArrayIfNoConstructorAnnotationsFound(): void
    {
        $reader = $this->createMock(AnnotationReader::class);
        $reader->expects($this->once())
            ->method('getMethodAnnotations')
            ->with($this->isInstanceOf(ReflectionMethod::class))
            ->willReturn([]);

        $extractor = new AnnotationExtractor($reader);

        $injections = $extractor->getConstructorInjections(ServiceAnnotation::class);

        self::assertCount(0, $injections);
    }
}
