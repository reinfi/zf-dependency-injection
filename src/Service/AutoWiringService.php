<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class AutoWiringService
{
    use CacheKeyTrait;

    /**
     * @var ResolverServiceInterface
     */
    private $resolverService;

    /**
     * @var StorageInterface
     */
    private $cache;

    /**
     * @param ResolverServiceInterface $resolverService
     * @param StorageInterface         $cache
     */
    public function __construct(
        ResolverServiceInterface $resolverService,
        StorageInterface $cache
    ) {
        $this->resolverService = $resolverService;
        $this->cache = $cache;
    }

    /**
     * @param ContainerInterface $container
     * @param string             $className
     * @param null|array         $options
     *
     * @return InjectionInterface[]|null
     */
    public function resolveConstructorInjection(
        ContainerInterface $container,
        string $className,
        ?array $options = null
    ): ?array {
        $injections = $this->getInjections($className, $options);

        if (empty($injections) && $options === null) {
            return null;
        }

        foreach ($injections as $index => $injection) {
            $injections[$index] = $injection($container);
        }

        return array_merge($injections, $options ?? []);
    }

    /**
     * @param string $className
     *
     * @return InjectionInterface[]
     */
    private function getInjections(string $className, ?array $options = null): array
    {
        $cacheKey = $this->buildCacheKey($className);

        if ($this->cache->hasItem($cacheKey)) {
            $cachedItem = $this->cache->getItem($cacheKey);

            if (is_array($cachedItem)) {
                return $cachedItem;
            }
        }

        $injections = $this->resolverService->resolve($className, $options);
        $this->cache->setItem($cacheKey, $injections);

        return $injections;
    }
}
