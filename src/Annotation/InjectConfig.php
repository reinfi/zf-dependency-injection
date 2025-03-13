<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Laminas\Config\Config;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Annotation
 * @deprecated 7.0.0 Use attributes from \Reinfi\DependencyInjection\Attribute namespace instead. Will be removed in 8.0.0.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectConfig extends AbstractAnnotation
{
    private readonly string $configPath;

    private readonly bool $asArray;

    public function __construct(array $values)
    {
        $this->asArray = isset($values['asArray']) ? (bool) $values['asArray'] : false;
        $this->configPath = $values['value'];
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        $container = $this->determineContainer($container);

        $resolvedConfig = $container->get(ConfigService::class)->resolve($this->configPath);

        if ($this->asArray && $resolvedConfig instanceof Config) {
            return $resolvedConfig->toArray();
        }

        return $resolvedConfig;
    }
}
