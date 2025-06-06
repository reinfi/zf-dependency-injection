<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorChain;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Test\Service\Service1;

final class ExtractorChainTest extends TestCase
{
    public function testItCallExtractorsForPropertyAnnotations(): void
    {
        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor2 = $this->createMock(ExtractorInterface::class);

        $extractor1->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service1::class)
            ->willReturn([]);

        $extractor2->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service1::class)
            ->willReturn([]);

        $extractorChain = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(0, $extractorChain->getPropertiesInjections(Service1::class));
    }

    public function testItOnlyCallsOneExtractorIfInjectionsFoundInProperties(): void
    {
        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor2 = $this->createMock(ExtractorInterface::class);

        $injection = $this->createMock(InjectionInterface::class);

        $extractor1->expects($this->once())
            ->method('getPropertiesInjections')
            ->with(Service1::class)
            ->willReturn([$injection]);

        $extractor2->expects($this->never())
            ->method('getPropertiesInjections');

        $extractorChain = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(1, $extractorChain->getPropertiesInjections(Service1::class));
    }

    public function testItCallsAllExtractorsForConstructorInjections(): void
    {
        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor2 = $this->createMock(ExtractorInterface::class);

        $extractor1->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service1::class)
            ->willReturn([]);

        $extractor2->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service1::class)
            ->willReturn([]);

        $extractorChain = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(0, $extractorChain->getConstructorInjections(Service1::class));
    }

    public function testItOnlyCallsOneExtractorIfInjectionsFoundInConstructor(): void
    {
        $extractor1 = $this->createMock(ExtractorInterface::class);
        $extractor2 = $this->createMock(ExtractorInterface::class);

        $injection = $this->createMock(InjectionInterface::class);

        $extractor1->expects($this->once())
            ->method('getConstructorInjections')
            ->with(Service1::class)
            ->willReturn([$injection]);

        $extractor2->expects($this->never())
            ->method('getConstructorInjections');

        $extractorChain = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(1, $extractorChain->getConstructorInjections(Service1::class));
    }
}
