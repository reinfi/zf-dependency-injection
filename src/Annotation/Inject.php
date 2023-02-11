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
final class Inject implements AnnotationInterface
{
    /**
     * @var string
     */
    public $value;

    public function __invoke(ContainerInterface $container)
    {
        return $container->get($this->value);
    }
}
