<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\ConfigProvider;

final class ConfigProviderTest extends TestCase
{
    public function testItReturnsDependencies(): void
    {
        $configProvider = new ConfigProvider();

        self::assertArrayHasKey('dependencies', $configProvider(), 'Config provider should contain dependencies');
    }
}
