<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Laminas\Config\Config;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Service\ConfigService;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectConfigTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    /**
     * @test
     */
    public function itCallsConfigServiceFromContainerWithValue()
    {
        $inject = new InjectConfig(['value' => 'reinfi.di.test']);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $this->assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     */
    public function itCallsConfigServiceFromPluginManagerWithValue()
    {
        $inject = new InjectConfig(['value' => 'reinfi.di.test']);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $this->assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     */
    public function itReturnsArrayIfPropertyIsSet()
    {
        $inject = new InjectConfig(['value' => 'reinfi.di.test', 'asArray' => true]);

        $config = $this->prophesize(Config::class);
        $config->toArray()->shouldBeCalled()->willReturn([ true ]);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn($config->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $this->assertEquals(
            [ true ],
            $inject($container->reveal()),
            'Invoke should return array containing true'
        );
    }

}
