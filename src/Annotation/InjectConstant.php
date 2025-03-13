<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Annotation
 * @deprecated 7.0.0 Use attributes from \Reinfi\DependencyInjection\Attribute namespace instead. Will be removed in 8.0.0.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectConstant implements AnnotationInterface
{
    public string $value;

    public function __construct(array $values = [])
    {
        $this->value = $values['value'] ?? '';
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        return constant($this->value);
    }
}
