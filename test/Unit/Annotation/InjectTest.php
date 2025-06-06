<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
final class InjectTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $inject = new Inject();
        $inject->value = InjectionService::class;

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        self::assertTrue($inject($container), 'Invoke should return true');
    }
}
