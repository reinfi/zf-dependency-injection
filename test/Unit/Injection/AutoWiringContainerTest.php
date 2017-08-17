<?php

namespace Reinfi\DependencyInjection\Unit\Injection;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiringContainer;

/**
 * @package Reinfi\DependencyInjection\Unit\Injection
 */
class AutoWiringContainerTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsContainer()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $injection = new AutoWiringContainer();

        $this->assertInstanceOf(
            ContainerInterface::class,
            $injection($container->reveal())
        );
    }
}