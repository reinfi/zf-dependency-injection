<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Reinfi\DependencyInjection\Module;

/**
 * @package Reinfi\DependencyInjection\Test\Unit
 */
class ModuleTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsConfig(): void
    {
        $module = new Module();

        self::assertIsArray(
            $module->getConfig(),
            'Modules config should be of type array'
        );
    }
}
