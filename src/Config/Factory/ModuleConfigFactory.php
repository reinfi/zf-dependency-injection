<?php

namespace Reinfi\DependencyInjection\Config\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;

/**
 * @package Reinfi\DependencyInjection\Config\Factory
 */
class ModuleConfigFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container): array
    {
        /** @var array $config */
        $config = $container->get('config');

        $moduleConfig = $config[ModuleConfig::CONFIG_KEY] ?? [];

        if (!is_array($moduleConfig)) {
            throw new \InvalidArgumentException('Module config must be type of array');
        }

        return $moduleConfig;
    }
}