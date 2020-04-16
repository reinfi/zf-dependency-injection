<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Laminas\Config\Config;
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
    private $configPath;

    /**
     * @var bool
     */
    private $asArray = false;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['asArray'])) {
            $this->asArray = (bool) $values['asArray'];
        }

        $this->configPath = $values['value'];
    }
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        $container = $this->determineContainer($container);

        $resolvedConfig = $container->get(ConfigService::class)->resolve($this->configPath);

        if ($this->asArray && $resolvedConfig instanceof Config) {
            return $resolvedConfig->toArray();
        }

        return $resolvedConfig;
    }
}
