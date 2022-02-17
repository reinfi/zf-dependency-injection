<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorChain;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Test\Service\Service1;

class ExtractorChainTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itCallExtractorsForPropertyAnnotations(): void
    {
        $extractor1 = $this->prophesize(ExtractorInterface::class);
        $extractor2 = $this->prophesize(ExtractorInterface::class);

        $extractor1->getPropertiesInjections(Service1::class)->willReturn([])->shouldBeCalled();
        $extractor2->getPropertiesInjections(Service1::class)->willReturn([])->shouldBeCalled();

        $extractor = new ExtractorChain(
            [
                $extractor1->reveal(),
                $extractor2->reveal(),
            ]
        );

        self::assertCount(0, $extractor->getPropertiesInjections(Service1::class));
    }

    /**
     * @test
     */
    public function itCallExtractorsForConstructorAnnotations(): void
    {
        $extractor1 = $this->prophesize(ExtractorInterface::class);
        $extractor2 = $this->prophesize(ExtractorInterface::class);

        $extractor1->getConstructorInjections(Service1::class)->willReturn([])->shouldBeCalled();
        $extractor2->getConstructorInjections(Service1::class)->willReturn([])->shouldBeCalled();

        $extractor = new ExtractorChain(
            [
                $extractor1->reveal(),
                $extractor2->reveal(),
            ]
        );

        self::assertCount(0, $extractor->getConstructorInjections(Service1::class));
    }
}
