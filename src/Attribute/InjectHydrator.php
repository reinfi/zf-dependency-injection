<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class InjectHydrator extends AbstractInjectPluginManager
{
    public const PLUGIN_MANAGER = 'HydratorManager';
}
