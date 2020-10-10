<?php

namespace Reinfi\DependencyInjection\Test\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Module;
use Laminas\Console\Adapter\AdapterInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit
 */
class ModuleTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    /**
     * @test
     */
    public function itReturnsConfig()
    {
        $module = new Module();

        $this->assertIsArray(
            $module->getConfig(),
            'Modules config should be of type array'
        );
    }

    /**
     * @test
     */
    public function itReturnsConsoleUsage()
    {
        $module = new Module();

        $consoleAdapter = $this->prophesize(AdapterInterface::class);

        $this->assertIsArray(
            $module->getConsoleUsage($consoleAdapter->reveal()),
            'Modules console usage should be of type array'
        );
    }
}
