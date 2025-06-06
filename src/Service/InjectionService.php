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

    public function __construct(
        private readonly ExtractorInterface $extractor,
        private readonly CacheService $cacheService
    ) {
    }

    /**
     * @param class-string       $className
     *
     * @return array|false
     */
    public function resolveConstructorInjection(ContainerInterface $container, string $className)
    {
        $injections = $this->getInjections($className);

        if ($injections === []) {
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

        if ($this->cacheService->has($cacheKey)) {
            $cachedItem = $this->cacheService->get($cacheKey);

            if (is_array($cachedItem)) {
                // @phpstan-ignore-next-line
                return $cachedItem;
            }
        }

        $injections = array_merge(
            $this->extractor->getPropertiesInjections($className),
            $this->extractor->getConstructorInjections($className)
        );
        $this->cacheService->set($cacheKey, $injections);

        return $injections;
    }
}
