<?php

namespace Reinfi\DependencyInjection\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\CacheService;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\Cache\StorageFactory;

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
        /** @var array $config */
        $config = $container->get(ModuleConfig::class);

        $cacheAdapter = $config['cache'] ?? Memory::class;

        $cacheOptions = $config['cache_options'] ?? [];

        $cachePlugins = $config['cache_plugins'] ?? [];

        $cache = StorageFactory::factory(
            [
                'adapter' => [
                    'name'    => $cacheAdapter,
                    'options' => $cacheOptions,
                ],
                'plugins' => $cachePlugins,
            ]
        );
        
        return new CacheService($cache);
    }
}
