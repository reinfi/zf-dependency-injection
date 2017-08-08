<?php

namespace Reinfi\DependencyInjection\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\CacheService;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\Cache\StorageFactory;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class CacheServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return CacheService
     */
    public function __invoke(ContainerInterface $container): CacheService
    {
        /** @var Config $config */
        $config = $container->get(ModuleConfig::class);

        $cache = StorageFactory::adapterFactory(
            $config->get('cache', Memory::class),
            $config->get('cache_options', [])
        );

        return new CacheService($cache);
    }
}