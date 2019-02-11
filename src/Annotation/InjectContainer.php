<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectContainer implements AnnotationInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        return $container;
    }
}
