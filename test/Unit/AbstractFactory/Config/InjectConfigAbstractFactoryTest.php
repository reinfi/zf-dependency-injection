<?php

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

    /**
     * @test
     */
    public function itCanCreateServiceWithConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        /** @var ContainerInterface $container */
        $container = $this
            ->prophesize(ContainerInterface::class)
            ->reveal();

        self::assertTrue(
            $factory->canCreate(
                $container,
                'Config.reinfi.di.test'
            ),
            'factory should be able to create service'
        );
    }

    /**
     * @test
     */
    public function itCanNotCreateServiceWithNonConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        /** @var ContainerInterface $container */
        $container = $this
            ->prophesize(ContainerInterface::class)
            ->reveal();

        self::assertFalse(
            $factory->canCreate(
                $container,
                'service.reinfi.di.test'
            ),
            'factory should not be able to create service'
        );
    }

    /**
     * @test
     */
    public function itCallsConfigServiceForConfigPattern(): void
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
