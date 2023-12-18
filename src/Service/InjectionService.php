<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class InjectionService
{
    use CacheKeyTrait;

    private ExtractorInterface $extractor;

    private CacheService $cache;

    public function __construct(
        ExtractorInterface $extractor,
        CacheService $cache
    ) {
        $this->extractor = $extractor;
        $this->cache = $cache;
    }

    /**
     * @param class-string       $className
     *
     * @return array|false
     */
    public function resolveConstructorInjection(ContainerInterface $container, string $className)
    {
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

        if ($this->cache->has($cacheKey)) {
            $cachedItem = $this->cache->get($cacheKey);

            if (is_array($cachedItem)) {
                return $cachedItem;
            }
        }

        $injections = array_merge(
            $this->extractor->getPropertiesInjections($className),
            $this->extractor->getConstructorInjections($className)
        );
        $this->cache->set($cacheKey, $injections);

        return $injections;
    }
}
