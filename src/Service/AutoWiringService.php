<?php

namespace Reinfi\DependencyInjection\Service;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class AutoWiringService
{
    use CacheKeyTrait;

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
            $injections = $this->findInjections($className);
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

    /**
     * @param string $className
     *
     * @return array
     */
    public function findInjections(string $className): array
    {
        $reflClass = new ReflectionClass($className);

        $constructor = $reflClass->getConstructor();

        if ($constructor === null) {
            return [];
        }

        $parameters = $constructor->getParameters();

        return array_map([$this, 'retrieveInjection'], $parameters);
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return AutoWiring
     */
    protected function retrieveInjection(
        ReflectionParameter $parameter
    ): AutoWiring {
        return new AutoWiring($parameter->getClass()->getName());
    }
}