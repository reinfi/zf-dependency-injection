<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Psr\SimpleCache\CacheInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class CacheService
{
    public function __construct(
        private readonly CacheInterface $cache
    ) {
    }

    public function get(string $key): ?array
    {
        $cachedValue = $this->cache->get($key);
        if (! is_array($cachedValue)) {
            return null;
        }

        return $cachedValue;
    }

    public function set(string $key, array $value): bool
    {
        return $this->cache->set($key, $value);
    }

    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }
}
