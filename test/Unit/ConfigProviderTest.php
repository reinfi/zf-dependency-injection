<?php

namespace Reinfi\DependencyInjection\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\ConfigProvider;

/**
 * @package Reinfi\DependencyInjection\Unit
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsConfig()
    {
        $provider = new ConfigProvider();

        $this->assertInternalType(
            'array',
            $provider(),
            'Modules config should be of type array'
        );
    }

    /**
     * @test
     */
    public function itReturnsConfigKeyDependencies()
    {
        $provider = new ConfigProvider();

        $config = $provider();

        $this->assertArrayHasKey(
            'dependencies',
            $config,
            'Modules config should have a key "dependencies"'
        );
    }
}