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
    public function __invoke(ContainerInterface $container): ConfigService
    {
        $containerConfig = $container->get('config');

        assert(is_array($containerConfig));
        $config = new Config($containerConfig);

        return new ConfigService($config);
    }
}
