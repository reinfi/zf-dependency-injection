<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
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
    /**
     * @test
     */
    public function itResolvesPropertyAnnotations()
    {
        $annotation = $this->prophesize(AnnotationInterface::class);

        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getPropertyAnnotation(
            Argument::type(\ReflectionProperty::class),
            Argument::exact(AnnotationInterface::class)
        )->willReturn($annotation->reveal())
        ->shouldBeCalledTimes(2);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getPropertiesInjections(ServiceAnnotation::class);

        $this->assertCount(2, $injections);
        $this->assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    /**
     * @test
     */
    public function itResolvesConstructorAnnotations()
    {
        $annotation = $this->prophesize(AnnotationInterface::class);

        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getMethodAnnotations(
            Argument::type(\ReflectionMethod::class)
        )->willReturn([$annotation->reveal()])
            ->shouldBeCalledTimes(1);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getConstructorInjections(ServiceAnnotation::class);

        $this->assertCount(1, $injections);
        $this->assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    /**
     * @test
     */
    public function itReturnsEmptyArrayIfNoConstructorIsDefined()
    {
        $reader = $this->prophesize(AnnotationReader::class);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getConstructorInjections(Service2::class);

        $this->assertCount(0, $injections);
    }

    /**
     * @test
     */
    public function itReturnsEmptyArrayIfNoConstructorAnnotationIsDefined()
    {
        $reader = $this->prophesize(AnnotationReader::class);
        $reader->getMethodAnnotations(
            Argument::type(\ReflectionMethod::class)
        )->willReturn([])
            ->shouldBeCalledTimes(1);

        $extractor = new AnnotationExtractor($reader->reveal());

        $injections = $extractor->getConstructorInjections(Service1::class);

        $this->assertCount(0, $injections);
    }
}
