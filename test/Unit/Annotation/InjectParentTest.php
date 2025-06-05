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
final class InjectParentTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $injectParent = new InjectParent();
        $injectParent->value = InjectionService::class;

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        self::assertTrue($injectParent($container), 'Invoke should return true');
    }

    public function testItCallsParentContainerWhenPluginManager(): void
    {
        $injectParent = new InjectParent();
        $injectParent->value = InjectionService::class;

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($injectParent($pluginManager), 'Invoke should return true');
    }
}
