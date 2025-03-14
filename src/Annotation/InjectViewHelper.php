<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

/**
 * @package Reinfi\DependencyInjection\Annotation
 * @deprecated 7.0.0 Use attributes from \Reinfi\DependencyInjection\Attribute namespace instead. Will be removed in 8.0.0.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectViewHelper extends AbstractInjectPluginManager
{
    public const string PLUGIN_MANAGER = 'ViewHelperManager';
}
