<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectParent;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectParentTest extends TestCase
{
    use ProphecyTrait;

    public function testItCallsContainerWithValue(): void
    {
        $inject = new InjectParent(InjectionService::class);

        $container = $this->prophesize(ContainerInterface::class);

        $container->get(InjectionService::class)
            ->willReturn(true);

        self::assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    public function testItCallsParentContainerWhenPluginManager(): void
    {
        $inject = new InjectParent(InjectionService::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(InjectionService::class)
            ->willReturn(true);

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        self::assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }
}
