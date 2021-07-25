<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Psr\SimpleCache\CacheInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class CacheService
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function get(string $key): ?array
    {
        return $this->cache->get($key);
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
