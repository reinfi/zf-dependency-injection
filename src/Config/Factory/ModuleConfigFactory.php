<?php

namespace Reinfi\DependencyInjection\Config\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Config\Factory
 */
class ModuleConfigFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Config
     */
    public function __invoke(ContainerInterface $container): Config
    {
        $config = new Config($container->get('config'));

        if ($config->offsetExists(ModuleConfig::CONFIG_KEY)) {
            return $config->get(ModuleConfig::CONFIG_KEY);
        }

        return new Config([]);
    }
}