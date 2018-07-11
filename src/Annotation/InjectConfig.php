<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectConfig extends AbstractAnnotation
{
    /**
     * @var string
     */
    public $value;

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        $container = $this->determineContainer($container);

        return $container->get(ConfigService::class)->resolve($this->value);
    }
}
