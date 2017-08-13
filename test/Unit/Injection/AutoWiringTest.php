<?php

namespace Reinfi\DependencyInjection\Unit\Injection;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\Service1;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Unit\Injection
 */
class AutoWiringTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsServiceFromContainer()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $container->get(Service1::class)
            ->willReturn($service1->reveal());

        $injection = new AutoWiring(Service1::class);

        $this->assertInstanceOf(
            Service1::class,
            $injection($container->reveal())
        );
    }

    /**
     * @test
     */
    public function itReturnsServiceFromParentLocator()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $container->get(Service1::class)
            ->willReturn($service1->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->has(Service1::class)
            ->willReturn(false);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $injection = new AutoWiring(Service1::class);

        $this->assertInstanceOf(
            Service1::class,
            $injection($pluginManager->reveal())
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfServiceNotFound()
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(false);

        $injection = new AutoWiring(Service1::class);

        $injection($container->reveal());
    }
}