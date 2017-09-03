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
     * @throws \InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container): Config
    {
        $config = new Config($container->get('config'));

        if ($config->offsetExists(ModuleConfig::CONFIG_KEY)) {
            $moduleConfig = $config->get(ModuleConfig::CONFIG_KEY);

            if (!$moduleConfig instanceof Config) {
                throw new \InvalidArgumentException('Module config must be type of ' . Config::class);
            }

            return $moduleConfig;
        }

        return new Config([]);
    }
}