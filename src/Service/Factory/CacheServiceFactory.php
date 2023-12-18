<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Cache\Memory;
use Reinfi\DependencyInjection\Service\CacheService;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class CacheServiceFactory
{
    public function __invoke(ContainerInterface $container): CacheService
    {
        /** @var array $config */
        $config = $container->get(ModuleConfig::class);

        $cache = null;
        $cacheConfigValue = $config['cache'] ?? null;

        if ($cacheConfigValue === null) {
            $cache = new Memory();
        }

        if (is_string($cacheConfigValue)) {
            $cache = $container->get($cacheConfigValue);
        }

        if (is_callable($cacheConfigValue)) {
            $cache = $cacheConfigValue($container);
        }

        if (! $cache instanceof CacheInterface) {
            throw new InvalidArgumentException('config value for cache does not return a cache interface instance');
        }

        return new CacheService($cache);
    }
}
