<?php

namespace Reinfi\DependencyInjection\Annotation;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectFormElement extends AbstractInjectPluginManager
{
    /**
     * @var string
     */
    const PLUGIN_MANAGER = 'FormElementManager';
}