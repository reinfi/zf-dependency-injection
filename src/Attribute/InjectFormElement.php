<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class InjectFormElement extends AbstractInjectPluginManager
{
    public const string PLUGIN_MANAGER = 'FormElementManager';
}
