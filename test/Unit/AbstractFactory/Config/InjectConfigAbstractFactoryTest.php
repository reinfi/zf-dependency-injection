<?php

namespace Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config;

use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
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

        /** @var ServiceLocatorInterface $container */
        $container = $this
            ->prophesize(ServiceLocatorInterface::class)
            ->reveal();

        $this->assertTrue(
            $factory->canCreateServiceWithName(
                $container,
                'config.reinfi.di.test',
                'Config.reinfi.di.test'
            ),
            'factory should be able to create service'
        );

        $this->assertTrue(
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

        /** @var ServiceLocatorInterface $container */
        $container = $this
            ->prophesize(ServiceLocatorInterface::class)
            ->reveal();

        $this->assertFalse(
            $factory->canCreateServiceWithName(
                $container,
                'service.reinfi.di.test',
                'service.reinfi.di.test'
            ),
            'factory should not be able to create service'
        );

        $this->assertFalse(
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
            ->prophesize(ServiceLocatorInterface::class);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true);

        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $factory->canCreateServiceWithName(
            $container->reveal(),
            'config.reinfi.di.test',
            'Config.reinfi.di.test'
        );

        $this->assertTrue(
            $factory->createServiceWithName(
                $container->reveal(),
                'config.reinfi.di.test',
                'Config.reinfi.di.test'
            )
        );
    }
}
