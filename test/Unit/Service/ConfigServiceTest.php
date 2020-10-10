<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Exception\ConfigPathNotFoundException;
use Reinfi\DependencyInjection\Service\ConfigService;
use Laminas\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class ConfigServiceTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    /**
     * @test
     */
    public function itResolvesConfigPath()
    {
        $config = new Config(require __DIR__ . '/../../resources/config.php');

        $service = new ConfigService($config);

        $value = $service->resolve('test.value');

        $this->assertEquals(
            1,
            $value,
            'Value from config should be equal'
        );
    }

    /**
     * @test
     */
    public function itResolvesToNullIfValueNotFound()
    {
        $config = new Config(require __DIR__ . '/../../resources/config.php');

        $service = new ConfigService($config);

        $value = $service->resolve('test.valueNull');

        $this->assertNull(
            $value,
            'Value from config should be null'
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfValueMustExist()
    {
        $this->expectException(ConfigPathNotFoundException::class);

        $config = $this->prophesize(Config::class);
        $config->offsetExists('test')
            ->willReturn(true)
            ->shouldBeCalled();
        $config->offsetExists('valueMustExist')
            ->willReturn(false)
            ->shouldBeCalled();
        $config->get('test')
            ->willReturn($config->reveal());

        $service = new ConfigService($config->reveal());

        $service->resolve('test.valueMustExist!');
    }
}
