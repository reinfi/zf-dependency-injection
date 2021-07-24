<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectContainerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itCallsContainerWithValue(): void
    {
        $inject = new InjectContainer();

        $container = $this->prophesize(ContainerInterface::class);

        self::assertEquals(
            $container->reveal(),
            $inject($container->reveal()),
            'Invoke should return provided container'
        );
    }
}
