<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Laminas\Cache\Storage\StorageInterface;

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

    public function __construct(
        ExtractorInterface $extractor,
        StorageInterface $cache
    ) {
        $this->extractor = $extractor;
        $this->cache = $cache;
    }

    /**
     * @param ContainerInterface $container
     * @param class-string       $className
     *
     * @return array|false
     */
    public function resolveConstructorInjection(
        ContainerInterface $container,
        string $className
    ) {
        $injections = $this->getInjections($className);

        if (count($injections) === 0) {
            return false;
        }

        foreach ($injections as $index => $injection) {
            $injections[$index] = $injection($container);
        }

        return $injections;
    }

    /**
     * @param class-string $className
     *
     * @return InjectionInterface[]
     */
    private function getInjections(string $className): array
    {
        $cacheKey = $this->buildCacheKey($className);

        if ($this->cache->hasItem($cacheKey)) {
            $cachedItem = $this->cache->getItem($cacheKey);

            if (is_array($cachedItem)) {
                return $cachedItem;
            }
        }

        $injections = array_merge(
            $this->extractor->getPropertiesInjections($className),
            $this->extractor->getConstructorInjections($className)
        );
        $this->cache->setItem($cacheKey, $injections);

        return $injections;
    }
}
