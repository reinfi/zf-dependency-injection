<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\Inject;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $inject = new Inject(InjectionService::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        self::assertTrue($inject($container), 'Invoke should return true');
    }
}
