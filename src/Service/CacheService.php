<?php

namespace Reinfi\DependencyInjection\Service;

use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class CacheService implements StorageInterface
{
    /**
     * @var StorageInterface
     */
    private $cache;

    /**
     * @param StorageInterface $cache
     */
    public function __construct(StorageInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    public function setOptions($options)
    {
        $this->cache->setOptions($options);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOptions()
    {
        return $this->cache->getOptions();
    }

    /**
     * @inheritDoc
     */
    public function getItem($key, & $success = null, & $casToken = null)
    {
        return $this->cache->getItem($key, $success, $casToken);
    }

    /**
     * @inheritDoc
     */
    public function getItems(array $keys)
    {
        return $this->cache->getItems($keys);
    }

    /**
     * @inheritDoc
     */
    public function hasItem($key)
    {
        return $this->cache->hasItem($key);
    }

    /**
     * @inheritDoc
     */
    public function hasItems(array $keys)
    {
        return $this->cache->hasItems($keys);
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key)
    {
        return $this->cache->getMetadata($key);
    }

    /**
     * @inheritDoc
     */
    public function getMetadatas(array $keys)
    {
        return $this->cache->getMetadatas($keys);
    }

    /**
     * @inheritDoc
     */
    public function setItem($key, $value)
    {
        return $this->cache->setItem($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function setItems(array $keyValuePairs)
    {
        return $this->cache->setItems($keyValuePairs);
    }

    /**
     * @inheritDoc
     */
    public function addItem($key, $value)
    {
        return $this->cache->addItem($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function addItems(array $keyValuePairs)
    {
        return $this->cache->addItems($keyValuePairs);
    }

    /**
     * @inheritDoc
     */
    public function replaceItem($key, $value)
    {
        return $this->cache->replaceItem($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function replaceItems(array $keyValuePairs)
    {
        return $this->cache->replaceItems($keyValuePairs);
    }

    /**
     * @inheritDoc
     */
    public function checkAndSetItem($token, $key, $value)
    {
        return $this->cache->checkAndSetItem($token, $key, $value);
    }

    /**
     * @inheritDoc
     */
    public function touchItem($key)
    {
        return $this->cache->touchItem($key);
    }

    /**
     * @inheritDoc
     */
    public function touchItems(array $keys)
    {
        return $this->cache->touchItems($keys);
    }

    /**
     * @inheritDoc
     */
    public function removeItem($key)
    {
        return $this->cache->removeItem($key);
    }

    /**
     * @inheritDoc
     */
    public function removeItems(array $keys)
    {
        return $this->cache->removeItems($keys);
    }

    /**
     * @inheritDoc
     */
    public function incrementItem($key, $value)
    {
        return $this->cache->incrementItem($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function incrementItems(array $keyValuePairs)
    {
        return $this->cache->incrementItems($keyValuePairs);
    }

    /**
     * @inheritDoc
     */
    public function decrementItem($key, $value)
    {
        return $this->cache->decrementItem($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function decrementItems(array $keyValuePairs)
    {
        return $this->cache->decrementItems($keyValuePairs);
    }

    /**
     * @inheritDoc
     */
    public function getCapabilities()
    {
        return $this->cache->getCapabilities();
    }
}