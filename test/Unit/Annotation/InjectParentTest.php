<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectParent;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectParentTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itCallsContainerWithValue(): void
    {
        $inject = new InjectParent();

        $inject->value = InjectionService::class;

        $container = $this->prophesize(ContainerInterface::class);

        $container->get(InjectionService::class)
            ->willReturn(true);

        $this->assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     */
    public function itCallsParentContainerWhenPluginManager(): void
    {
        $inject = new InjectParent();

        $inject->value = InjectionService::class;

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(InjectionService::class)
            ->willReturn(true);

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $this->assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }
}
