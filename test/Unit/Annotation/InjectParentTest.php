<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectParent;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectParentTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $inject = new InjectParent();
        $inject->value = InjectionService::class;

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo(InjectionService::class))
            ->willReturn(true);

        self::assertTrue($inject($container), 'Invoke should return true');
    }

    public function testItCallsParentContainerWhenPluginManager(): void
    {
        $inject = new InjectParent();
        $inject->value = InjectionService::class;

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo(InjectionService::class))
            ->willReturn(true);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($inject($pluginManager), 'Invoke should return true');
    }
}
