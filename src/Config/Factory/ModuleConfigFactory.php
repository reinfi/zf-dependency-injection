<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Config\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;

/**
 * @package Reinfi\DependencyInjection\Config\Factory
 */
class ModuleConfigFactory
{
    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container): array
    {
        /** @var array $config */
        $config = $container->get('config');

        $moduleConfig = $config[ModuleConfig::CONFIG_KEY] ?? [];

        if (!is_array($moduleConfig)) {
            throw new InvalidArgumentException('Module config must be type of array');
        }

        return $moduleConfig;
    }
}
