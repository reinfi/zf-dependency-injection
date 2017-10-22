<?php

namespace Reinfi\DependencyInjection\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Module;
use Zend\Console\Adapter\AdapterInterface;

/**
 * @package Reinfi\DependencyInjection\Unit
 */
class ModuleTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsConfig()
    {
        $module = new Module();

        $this->assertInternalType(
            'array',
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

        $this->assertInternalType(
            'array',
            $module->getConsoleUsage($consoleAdapter->reveal()),
            'Modules console usage should be of type array'
        );
    }
}