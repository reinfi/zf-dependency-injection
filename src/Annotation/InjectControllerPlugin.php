<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectControllerPlugin extends AbstractInjectPluginManager
{
    public const PLUGIN_MANAGER = 'ControllerPluginManager';
}
