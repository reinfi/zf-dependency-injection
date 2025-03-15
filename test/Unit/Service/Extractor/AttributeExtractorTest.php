<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\Extractor\AttributeExtractor;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\ServiceAttribute;
use Reinfi\DependencyInjection\Test\Service\ServiceAttributeConstructor;

class AttributeExtractorTest extends TestCase
{
    public function testItResolvesPropertyAnnotations(): void
    {
        $isPhp8OrAbove = version_compare(PHP_VERSION, '8.0.0') >= 0;

        if (! $isPhp8OrAbove) {
            $this->markTestSkipped('Not a php version of 8.0 or above');
        }

        $extractor = new AttributeExtractor();

        $injections = $extractor->getPropertiesInjections(ServiceAttribute::class);

        self::assertCount(3, $injections);
        self::assertContainsOnlyInstancesOf(InjectionInterface::class, $injections);
    }

    public function testItResolvesConstructorAnnotations(): void
    {
        $isPhp8OrAbove = version_compare(PHP_VERSION, '8.0.0') >= 0;

        if (! $isPhp8OrAbove) {
            $this->markTestSkipped('Not a php version of 8.0 or above');
        }

        $extractor = new AttributeExtractor();

        $injections = $extractor->getConstructorInjections(ServiceAttributeConstructor::class);

        self::assertCount(1, $injections);
        self::assertContainsOnlyInstancesOf(InjectionInterface::class, $injections);
    }

    public function testItReturnsEmptyArrayIfNoConstructorIsDefined(): void
    {
        $extractor = new AttributeExtractor();

        $injections = $extractor->getConstructorInjections(Service2::class);

        self::assertCount(0, $injections);
    }

    public function testItReturnsEmptyArrayIfNoConstructorAttributeIsDefined(): void
    {
        $isPhp8OrAbove = version_compare(PHP_VERSION, '8.0.0') >= 0;

        if (! $isPhp8OrAbove) {
            $this->markTestSkipped('Not a php version of 8.0 or above');
        }

        $extractor = new AttributeExtractor();

        $injections = $extractor->getConstructorInjections(Service1::class);

        self::assertCount(0, $injections);
    }
}
