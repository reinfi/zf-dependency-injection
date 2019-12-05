<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectTest extends TestCase
{
    /**
     * @test
     */
    public function itCallsContainerWithValue()
    {
        $inject = new Inject();

        $inject->value = InjectionService::class;

        $container = $this->prophesize(ContainerInterface::class);

        $container->get(InjectionService::class)
            ->willReturn(true);

        $this->assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }
}
