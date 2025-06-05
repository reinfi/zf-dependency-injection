<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectParent;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectParentTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $injectParent = new InjectParent(InjectionService::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        self::assertTrue($injectParent($container), 'Invoke should return true');
    }

    public function testItCallsParentContainerWhenPluginManager(): void
    {
        $injectParent = new InjectParent(InjectionService::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($injectParent($pluginManager), 'Invoke should return true');
    }
}
