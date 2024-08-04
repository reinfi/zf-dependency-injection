<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Traits;

use Reinfi\DependencyInjection\Config\ModuleConfig;

/**
 * @package Reinfi\DependencyInjection\Traits
 */
trait CacheKeyTrait
{
    /**
     * To avoid naming collisions
     */
    private function buildCacheKey(string $className): string
    {
        return md5(ModuleConfig::CONFIG_KEY . '.' . $className);
    }
}
