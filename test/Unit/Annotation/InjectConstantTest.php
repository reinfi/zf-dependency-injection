<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectConstant;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Test\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectConstantTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldConvertScalarTypes()
    {
        $injectScalar = new InjectConstant();
        $injectScalar->value = Service2::class . '::CONSTANT';

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(InjectionService::class)->willReturn(true);

        $this->assertSame(Service2::CONSTANT, $injectScalar($container->reveal()));
    }
}
