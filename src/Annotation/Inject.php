<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 * @deprecated 2.0.0 Use \Reinfi\DependencyInjection\Attribute\Inject instead
 */
final class Inject implements AnnotationInterface
{
    public string $value;

    public function __construct()
    {
        trigger_deprecation(
            'reinfi/dependency-injection',
            '2.0.0',
            'The %s annotation is deprecated. Use \Reinfi\DependencyInjection\Attribute\Inject instead.',
            self::class
        );
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        return $container->get($this->value);
    }
}
