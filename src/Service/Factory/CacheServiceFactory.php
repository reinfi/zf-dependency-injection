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

        $cacheAdapter = $config->get('cache', Memory::class);

        $cacheOptions = $config->get('cache_options', []);
        if ($cacheOptions instanceof Config) {
            $cacheOptions = $cacheOptions->toArray();
        }

        $cachePlugins = $config->get('cache_plugins', []);
        if ($cachePlugins instanceof Config) {
            $cachePlugins = $cachePlugins->toArray();
        }

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
