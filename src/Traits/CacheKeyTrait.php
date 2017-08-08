<?php


namespace Reinfi\DependencyInjection\Traits;

use Reinfi\DependencyInjection\Config\ModuleConfig;

/**
 * Trait CacheKeyTrait
 *
 * @package Reinfi\DependencyInjection\Traits
 */
trait CacheKeyTrait
{
    /**
     * To avoid naming collisions
     *
     * @param string $className
     *
     * @return string
     */
    private function buildCacheKey(string $className): string
    {
        return md5(ModuleConfig::CONFIG_KEY . '.' . $className);
    }
}