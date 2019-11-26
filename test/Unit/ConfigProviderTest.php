<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\ConfigProvider;

class ConfigProviderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsDependencies(): void
    {
        $configProvider = new ConfigProvider();

        $this->assertArrayHasKey(
            'dependencies',
            $configProvider(),
            'Config provider should contain dependencies',
        );
    }
}
