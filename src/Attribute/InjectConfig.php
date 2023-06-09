<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;
use Laminas\Config\Config;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class InjectConfig extends AbstractAttribute
{
    private string $configPath;

    private bool $asArray;

    public function __construct(string $configPath, bool $asArray = false)
    {
        $this->configPath = $configPath;
        $this->asArray = $asArray;
    }

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
