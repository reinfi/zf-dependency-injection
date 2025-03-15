<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Injection;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Injection
 */
class AutoWiringContainerTest extends TestCase
{
    public function testItReturnsContainer(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $injection = new AutoWiringContainer();

        self::assertInstanceOf(ContainerInterface::class, $injection($container));
    }
}
