<?php

namespace Reinfi\DependencyInjection\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectContainer;

/**
 * @package Reinfi\DependencyInjection\Unit\Annotation
 */
class InjectContainerTest extends TestCase
{
    /**
     * @test
     */
    public function itCallsContainerWithValue()
    {
        $inject = new InjectContainer();

        $container = $this->prophesize(ContainerInterface::class);

        $this->assertEquals(
            $container->reveal(),
            $inject($container->reveal()),
            'Invoke should return provided container'
        );
    }
}
