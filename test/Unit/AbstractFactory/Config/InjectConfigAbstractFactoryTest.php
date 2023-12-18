<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\AbstractFactory\Config\InjectConfigAbstractFactory;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config
 */
class InjectConfigAbstractFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testItCanCreateServiceWithConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        /** @var ContainerInterface $container */
        $container = $this
            ->prophesize(ContainerInterface::class)
            ->reveal();

        self::assertTrue(
            $factory->canCreate($container, 'Config.reinfi.di.test'),
            'factory should be able to create service'
        );
    }

    public function testItCanNotCreateServiceWithNonConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        /** @var ContainerInterface $container */
        $container = $this
            ->prophesize(ContainerInterface::class)
            ->reveal();

        self::assertFalse(
            $factory->canCreate($container, 'service.reinfi.di.test'),
            'factory should not be able to create service'
        );
    }

    public function testItCallsConfigServiceForConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        $container = $this
            ->prophesize(ContainerInterface::class);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true)
            ->shouldBeCalled();

        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $factory->canCreate($container->reveal(), 'Config.reinfi.di.test');

        $factory(
            $container->reveal(),
            'config.reinfi.di.test',
        );
    }
}
