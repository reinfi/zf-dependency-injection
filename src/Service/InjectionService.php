<?php

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class InjectionService
{
    use CacheKeyTrait;

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var StorageInterface
     */
    private $cache;

    /**
     * @param ExtractorInterface $extractor
     * @param StorageInterface   $cache
     */
    public function __construct(
        ExtractorInterface $extractor,
        StorageInterface $cache
    ) {
        $this->extractor = $extractor;
        $this->cache = $cache;
    }

    /**
     * @param ContainerInterface $container
     * @param string             $className
     *
     * @return array|bool
     */
    public function resolveConstructorInjection(
        ContainerInterface $container,
        string $className
    ) {
        $cacheKey = $this->buildCacheKey($className);

        /** @var InjectionInterface[] $injections */
        if ($this->cache->hasItem($cacheKey)) {
            $injections = $this->cache->getItem($cacheKey);
        } else {
            $injections = array_merge(
                $this->extractor->getPropertiesInjections($className),
                $this->extractor->getConstructorInjections($className)
            );
            $this->cache->setItem($cacheKey, $injections);
        }

        if (count($injections) === 0) {
            return false;
        }

        foreach ($injections as $index => $injection) {
            $injections[$index] = $injection($container);
        }

        return $injections;
    }

}