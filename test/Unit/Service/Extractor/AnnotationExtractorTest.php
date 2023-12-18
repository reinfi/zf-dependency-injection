<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionMethod;
use ReflectionProperty;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor
 */
class AnnotationExtractorTest extends TestCase
{
    use ProphecyTrait;

    public function testItResolvesPropertyAnnotations(): void
    {
        $annotation = $this->prophesize(AnnotationInterface::class);

        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getPropertyAnnotation(
            Argument::type(ReflectionProperty::class),
            Argument::exact(AnnotationInterface::class)
        )->willReturn($annotation->reveal())
            ->shouldBeCalledTimes(3);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getPropertiesInjections(ServiceAnnotation::class);

        self::assertCount(3, $injections);
        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItResolvesConstructorAnnotations(): void
    {
        $annotation = $this->prophesize(AnnotationInterface::class);

        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getMethodAnnotations(Argument::type(ReflectionMethod::class))->willReturn([$annotation->reveal()])
            ->shouldBeCalledTimes(1);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getConstructorInjections(ServiceAnnotation::class);

        self::assertCount(1, $injections);
        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItReturnsEmptyArrayIfNoConstructorIsDefined(): void
    {
        $reader = $this->prophesize(AnnotationReader::class);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getConstructorInjections(Service2::class);

        self::assertCount(0, $injections);
    }

    public function testItReturnsEmptyArrayIfNoConstructorAnnotationIsDefined(): void
    {
        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getMethodAnnotations(Argument::type(ReflectionMethod::class))->willReturn([])
            ->shouldBeCalledTimes(1);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getConstructorInjections(Service1::class);

        self::assertCount(0, $injections);
    }
}
