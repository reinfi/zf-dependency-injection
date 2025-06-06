<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Traits;

use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;

/**
 * @package Reinfi\DependencyInjection\Traits
 */
trait WarmupTrait
{
    use CacheKeyTrait;

    private function warmupConfig(
        array $factoriesConfig,
        ExtractorInterface $extractor,
        ResolverServiceInterface $resolverService,
        CacheService $cacheService
    ): void {
        array_walk(
            $factoriesConfig,
            function ($factoryClass, $className) use ($extractor, $resolverService, $cacheService): void {
                if (! is_string($factoryClass)) {
                    return;
                }

                $injections = $this->handleService($className, $factoryClass, $extractor, $resolverService);

                if (count($injections) > 0) {
                    $cacheService->set($this->buildCacheKey($className), $injections);
                }
            }
        );
    }

    private function handleService(
        string $className,
        string $factoryClass,
        ExtractorInterface $extractor,
        ResolverServiceInterface $resolverService
    ): array {
        if ($factoryClass === InjectionFactory::class) {
            return $this->warmupInjection($extractor, $className);
        }

        if ($factoryClass === AutoWiringFactory::class) {
            return $this->warmupAutoWiring($resolverService, $className);
        }

        return [];
    }

    private function warmupInjection(ExtractorInterface $extractor, string $className): array
    {
        return array_merge(
            $extractor->getPropertiesInjections($className),
            $extractor->getConstructorInjections($className)
        );
    }

    private function warmupAutoWiring(ResolverServiceInterface $resolverService, string $className): array
    {
        return $resolverService->resolve($className);
    }
}
