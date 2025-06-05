<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\InjectionNotPossibleException;
use Reinfi\DependencyInjection\Test\Service\NoPluginManagerAttribute;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;

final class AbstractInjectPluginManagerTest extends TestCase
{
    public function testItThrowsExceptionIfNotInstanceOfPluginManager(): void
    {
        $this->expectException(InjectionNotPossibleException::class);

        $noPluginManagerAttribute = new NoPluginManagerAttribute(Service1::class);

        $noPluginManagerClass = $this->createMock(Service2::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with('NOT-A-PLUGIN-MANAGER')
            ->willReturn($noPluginManagerClass);

        $noPluginManagerAttribute($container);
    }
}
