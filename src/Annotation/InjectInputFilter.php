<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectInputFilter extends AbstractInjectPluginManager
{
    /**
     * @var string
     */
    const PLUGIN_MANAGER = 'InputFilterManager';
}
