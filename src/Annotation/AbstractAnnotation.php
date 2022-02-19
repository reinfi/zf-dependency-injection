<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Reinfi\DependencyInjection\Injection\ContainerTrait;

/**
 * @package Reinfi\DependencyInjection\Annotation
 */
abstract class AbstractAnnotation implements AnnotationInterface
{
    use ContainerTrait;
}
