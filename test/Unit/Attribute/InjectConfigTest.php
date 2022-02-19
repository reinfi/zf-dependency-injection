<?php

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Laminas\Config\Config;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectConfig;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectConfigTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itCallsConfigServiceFromContainerWithValue(): void
    {
        $inject = new InjectConfig('reinfi.di.test');

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        self::assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     */
    public function itCallsConfigServiceFromPluginManagerWithValue(): void
    {
        $inject = new InjectConfig('reinfi.di.test');

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        self::assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     */
    public function itReturnsArrayIfPropertyIsSet(): void
    {
        $inject = new InjectConfig('reinfi.di.test', true);

        $config = $this->prophesize(Config::class);
        $config->toArray()->shouldBeCalled()->willReturn([ true ]);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn($config->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        self::assertEquals(
            [ true ],
            $inject($container->reveal()),
            'Invoke should return array containing true'
        );
    }
}
