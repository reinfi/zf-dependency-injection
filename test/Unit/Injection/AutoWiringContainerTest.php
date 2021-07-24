<?php

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

    /**
     * @test
     */
    public function itReturnsContainer(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $injection = new AutoWiringContainer();

        $this->assertInstanceOf(
            ContainerInterface::class,
            $injection($container->reveal())
        );
    }
}
