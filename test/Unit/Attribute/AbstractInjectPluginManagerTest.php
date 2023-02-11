<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\InjectionNotPossibleException;
use Reinfi\DependencyInjection\Test\Service\NoPluginManagerAttribute;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;

class AbstractInjectPluginManagerTest extends TestCase
{
    use ProphecyTrait;

    public function testItThrowsExceptionIfNotInstanceOfPluginManager(): void
    {
        $this->expectException(InjectionNotPossibleException::class);

        $attribute = new NoPluginManagerAttribute(Service1::class);

        $noPluginManagerClass = $this->prophesize(Service2::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('NOT-A-PLUGIN-MANAGER')->willReturn($noPluginManagerClass->reveal());

        $attribute($container->reveal());
    }
}
