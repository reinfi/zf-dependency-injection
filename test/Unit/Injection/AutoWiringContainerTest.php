<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Injection;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Injection
 */
class AutoWiringContainerTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsContainer(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $injection = new AutoWiringContainer();

        self::assertInstanceOf(ContainerInterface::class, $injection($container->reveal()));
    }
}
