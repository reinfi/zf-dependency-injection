<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\AbstractFactory\Config\InjectConfigAbstractFactory;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config
 */
class InjectConfigAbstractFactoryTest extends TestCase
{
    public function testItCanCreateServiceWithConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        $container = $this->createMock(ContainerInterface::class);

        self::assertTrue(
            $factory->canCreate($container, 'Config.reinfi.di.test'),
            'factory should be able to create service'
        );
    }

    public function testItCanNotCreateServiceWithNonConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        $container = $this->createMock(ContainerInterface::class);

        self::assertFalse(
            $factory->canCreate($container, 'service.reinfi.di.test'),
            'factory should not be able to create service'
        );
    }

    public function testItCallsConfigServiceForConfigPattern(): void
    {
        $factory = new InjectConfigAbstractFactory();

        $configService = $this->createMock(ConfigService::class);
        $configService->expects($this->once())
            ->method('resolve')
            ->with('reinfi.di.test')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(ConfigService::class)
            ->willReturn($configService);

        $factory->canCreate($container, 'Config.reinfi.di.test');

        $factory(
            $container,
            'config.reinfi.di.test',
        );
    }
}
