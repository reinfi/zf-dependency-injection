<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class ConfigServiceFactory
{
    public function __invoke(ContainerInterface $container): ConfigService
    {
        $config = $container->get('config');
        assert(is_array($config));

        return new ConfigService($config);
    }
}
