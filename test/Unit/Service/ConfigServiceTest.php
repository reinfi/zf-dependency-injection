<?php

namespace Reinfi\DependencyInjection\Unit\Service;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Exception\ConfigPathNotFoundException;
use Reinfi\DependencyInjection\Service\ConfigService;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Unit\Service
 */
class ConfigServiceTest extends TestCase
{
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

        $config = new Config(require __DIR__ . '/../../resources/config.php');

        $service = new ConfigService($config);

        $service->resolve('test.valueMustExist!');
    }
}