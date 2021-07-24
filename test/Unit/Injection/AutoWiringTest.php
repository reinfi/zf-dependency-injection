<?php

namespace Reinfi\DependencyInjection\Test\Unit\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Injection
 */
class AutoWiringTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsServiceFromContainer(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $container->get(Service1::class)
            ->willReturn($service1->reveal());

        $injection = new AutoWiring(Service1::class);

        self::assertInstanceOf(
            Service1::class,
            $injection($container->reveal())
        );
    }

    /**
     * @test
     */
    public function itReturnsServiceFromParentLocator(): void
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

        self::assertInstanceOf(
            Service1::class,
            $injection($pluginManager->reveal())
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(false);

        $injection = new AutoWiring(Service1::class);

        $injection($container->reveal());
    }
}
