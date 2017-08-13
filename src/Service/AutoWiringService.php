<?php

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class AutoWiringService
{
    use CacheKeyTrait;

    /**
     * @var ResolverService
     */
    private $resolverService;

    /**
     * @var StorageInterface
     */
    private $cache;

    /**
     * @param ResolverService  $resolverService
     * @param StorageInterface $cache
     */
    public function __construct(
        ResolverService $resolverService,
        StorageInterface $cache
    ) {
        $this->resolverService = $resolverService;
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
            $injections = $this->resolverService->resolve($className);
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