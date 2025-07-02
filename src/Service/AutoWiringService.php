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

    public function __construct(
        private readonly ResolverServiceInterface $resolverService,
        private readonly CacheService $cacheService
    ) {
    }

    /**
     * @return array<int|string, mixed>|null
     */
    public function resolveConstructorInjection(
        ContainerInterface $container,
        string $className,
        ?array $options = null
    ): ?array {
        $injections = $this->getInjections($className, $options);

        if ($injections === [] && $options === null) {
            return null;
        }

        foreach ($injections as $index => $injection) {
            $injections[$index] = $injection($container);
        }

        /** @var array<int|string, mixed> $injections */
        return $injections;
    }

    /**
     * @return InjectionInterface[]
     */
    private function getInjections(string $className, ?array $options = null): array
    {
        $cacheKey = $this->buildCacheKey($className);

        if ($options === null && $this->cacheService->has($cacheKey)) {
            $cachedItem = $this->cacheService->get($cacheKey);

            if (is_array($cachedItem)) {
                // @phpstan-ignore-next-line
                return $cachedItem;
            }
        }

        $injections = $this->resolverService->resolve($className, $options);

        if ($options === null) {
            $this->cacheService->set($cacheKey, $injections);
        }

        return $injections;
    }
}
