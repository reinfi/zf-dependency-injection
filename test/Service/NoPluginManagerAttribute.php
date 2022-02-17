<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Attribute;
use Reinfi\DependencyInjection\Attribute\AbstractInjectPluginManager;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class NoPluginManagerAttribute extends AbstractInjectPluginManager
{
    public const PLUGIN_MANAGER = 'NOT-A-PLUGIN-MANAGER';
}
