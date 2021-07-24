<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use Laminas\Config\Config;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Reinfi\DependencyInjection\Exception\ConfigPathNotFoundException;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class ConfigServiceTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itResolvesConfigPath(): void
    {
        $config = new Config(require __DIR__ . '/../../resources/config.php');

        $service = new ConfigService($config);

        $value = $service->resolve('test.value');

        self::assertEquals(
            1,
            $value,
            'Value from config should be equal'
        );
    }

    /**
     * @test
     */
    public function itResolvesToNullIfValueNotFound(): void
    {
        $config = new Config(require __DIR__ . '/../../resources/config.php');

        $service = new ConfigService($config);

        $value = $service->resolve('test.valueNull');

        self::assertNull(
            $value,
            'Value from config should be null'
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfValueMustExist(): void
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
