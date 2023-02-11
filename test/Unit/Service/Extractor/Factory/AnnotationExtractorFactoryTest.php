<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\Factory\AnnotationExtractorFactory;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory
 */
class AnnotationExtractorFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAnnotationExtractor(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $factory = new AnnotationExtractorFactory();

        self::assertInstanceOf(
            AnnotationExtractor::class,
            $factory($container->reveal())
        );
    }
}
