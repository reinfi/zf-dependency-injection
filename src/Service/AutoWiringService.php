<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class AutoWiringService
{
    use CacheKeyTrait;

    private ResolverServiceInterface $resolverService;

    private CacheService $cache;

    public function __construct(
        ResolverServiceInterface $resolverService,
        CacheService $cache
    ) {
        $this->resolverService = $resolverService;
        $this->cache = $cache;
    }

    /**
     * @return InjectionInterface[]|null
     */
    public function resolveConstructorInjection(
        ContainerInterface $container,
        string $className,
        ?array $options = null
    ): ?array {
        $injections = $this->getInjections($className, $options);

        if (count($injections) === 0 && $options === null) {
            return null;
        }

        foreach ($injections as $index => $injection) {
            $injections[$index] = $injection($container);
        }

        return $injections;
    }

    /**
     * @return InjectionInterface[]
     */
    private function getInjections(string $className, ?array $options = null): array
    {
        $cacheKey = $this->buildCacheKey($className);

        if ($options === null && $this->cache->has($cacheKey)) {
            $cachedItem = $this->cache->get($cacheKey);

            if (is_array($cachedItem)) {
                return $cachedItem;
            }
        }

        $injections = $this->resolverService->resolve($className, $options);

        if ($options === null) {
            $this->cache->set($cacheKey, $injections);
        }

        return $injections;
    }
}
