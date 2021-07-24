<?php

namespace Reinfi\DependencyInjection\Test\Unit;

use Laminas\Console\Adapter\AdapterInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Reinfi\DependencyInjection\Module;

/**
 * @package Reinfi\DependencyInjection\Test\Unit
 */
class ModuleTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsConfig(): void
    {
        $module = new Module();

        self::assertIsArray(
            $module->getConfig(),
            'Modules config should be of type array'
        );
    }
}
