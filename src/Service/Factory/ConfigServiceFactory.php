<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Factory;

use Laminas\Config\Config;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class ConfigServiceFactory
{
    public function __invoke(ContainerInterface $container): ConfigService
    {
        $containerConfig = $container->get('config');

        assert(is_array($containerConfig));
        $config = new Config($containerConfig);

        return new ConfigService($config);
    }
}
