<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Exception\ConfigPathNotFoundException;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class ConfigServiceTest extends TestCase
{
    public function testItResolvesConfigPath(): void
    {
        $config = require __DIR__ . '/../../resources/config.php';

        $service = new ConfigService($config);

        $value = $service->resolve('test.value');

        self::assertEquals(1, $value, 'Value from config should be equal');
    }

    public function testItResolvesToNullIfValueNotFound(): void
    {
        $config = require __DIR__ . '/../../resources/config.php';

        $service = new ConfigService($config);

        $value = $service->resolve('test.valueNull');

        self::assertNull($value, 'Value from config should be null');
    }

    public function testItThrowsExceptionIfValueMustExist(): void
    {
        $this->expectException(ConfigPathNotFoundException::class);

        $config = [
            'test' => [],
        ];

        $service = new ConfigService($config);

        $service->resolve('test.valueMustExist!');
    }
}
