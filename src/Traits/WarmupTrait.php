<?php

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

    /**
     * @param array                    $factoriesConfig
     * @param ExtractorInterface       $extractor
     * @param ResolverServiceInterface $resolverService
     * @param CacheService             $cache
     */
    private function warmupConfig(
        array $factoriesConfig,
        ExtractorInterface $extractor,
        ResolverServiceInterface $resolverService,
        CacheService $cache
    ) {
        array_walk(
            $factoriesConfig,
            function (
                $factoryClass,
                $className
            ) use ($extractor, $resolverService, $cache) {
                if (!is_string($factoryClass)) {
                    return;
                }
                $injections = $this->handleService(
                    $className,
                    $factoryClass,
                    $extractor,
                    $resolverService
                );

                if (count($injections) > 0) {
                    $cache->set(
                        $this->buildCacheKey($className),
                        $injections
                    );
                }
            }
        );
    }

    /**
     * @param string                   $className
     * @param string                   $factoryClass
     * @param ExtractorInterface       $extractor
     * @param ResolverServiceInterface $resolverService
     */
    private function handleService(
        string $className,
        string $factoryClass,
        ExtractorInterface $extractor,
        ResolverServiceInterface $resolverService
    ): array {
        if ($factoryClass === InjectionFactory::class) {
            return $this->warmupInjection(
                $extractor,
                $className
            );
        }

        if ($factoryClass === AutoWiringFactory::class) {
            return $this->warmupAutoWiring(
                $resolverService,
                $className
            );
        }

        return [];
    }

    /**
     * @param ExtractorInterface $extractor
     * @param string             $className
     */
    private function warmupInjection(
        ExtractorInterface $extractor,
        string $className
    ) {
        return array_merge(
            $extractor->getPropertiesInjections($className),
            $extractor->getConstructorInjections($className)
        );
    }

    /**
     * @param ResolverServiceInterface $resolverService
     * @param string                   $className
     */
    private function warmupAutoWiring(
        ResolverServiceInterface $resolverService,
        string $className
    ) {
        return $resolverService->resolve($className);
    }
}
