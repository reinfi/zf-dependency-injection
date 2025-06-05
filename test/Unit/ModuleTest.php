<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Module;

/**
 * @package Reinfi\DependencyInjection\Test\Unit
 */
final class ModuleTest extends TestCase
{
    public function testItReturnsConfig(): void
    {
        $module = new Module();

        self::assertIsArray($module->getConfig(), 'Modules config should be of type array');
    }
}
