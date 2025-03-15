<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorChain;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Test\Service\Service1;

class ExtractorChainTest extends TestCase
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

        $extractor = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(0, $extractor->getPropertiesInjections(Service1::class));
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

        $extractor = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(1, $extractor->getPropertiesInjections(Service1::class));
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

        $extractor = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(0, $extractor->getConstructorInjections(Service1::class));
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

        $extractor = new ExtractorChain([$extractor1, $extractor2]);

        self::assertCount(1, $extractor->getConstructorInjections(Service1::class));
    }
}
