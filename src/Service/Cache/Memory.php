<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Cache;

use BadMethodCallException;
use Psr\SimpleCache\CacheInterface;

class Memory implements CacheInterface
{
    private array $cachedItems = [];

    public function get($key, $default = null): mixed
    {
        return $this->cachedItems[$key] ?? $default;
    }

    public function set($key, $value, $ttl = null): bool
    {
        $this->cachedItems[$key] = $value;

        return true;
    }

    public function delete($key): bool
    {
        unset($this->cachedItems[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->cachedItems = [];

        return true;
    }

    public function getMultiple($keys, $default = null): iterable
    {
        throw new BadMethodCallException('Currently not implemented for memory cache');
    }

    public function setMultiple($values, $ttl = null): bool
    {
        throw new BadMethodCallException('Currently not implemented for memory cache');
    }

    public function deleteMultiple($keys): bool
    {
        throw new BadMethodCallException('Currently not implemented for memory cache');
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->cachedItems);
    }
}
