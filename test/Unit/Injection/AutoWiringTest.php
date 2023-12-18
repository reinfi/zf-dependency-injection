<?php

declare(strict_types=1);

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

    public function testItReturnsServiceFromContainer(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(true);

        $service1 = $this->prophesize(Service1::class);
        $container->get(Service1::class)
            ->willReturn($service1->reveal());

        $injection = new AutoWiring(Service1::class);

        self::assertInstanceOf(Service1::class, $injection($container->reveal()));
    }

    public function testItReturnsServiceFromParentLocator(): void
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

        self::assertInstanceOf(Service1::class, $injection($pluginManager->reveal()));
    }

    public function testItThrowsExceptionIfServiceNotFound(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(Service1::class)
            ->willReturn(false);

        $injection = new AutoWiring(Service1::class);

        $injection($container->reveal());
    }
}
