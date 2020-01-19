<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;
use Laminas\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class ConfigServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ConfigService
     */
    public function __invoke(ContainerInterface $container): ConfigService
    {
        $config = new Config($container->get('config'));

        return new ConfigService($config);
    }
}
