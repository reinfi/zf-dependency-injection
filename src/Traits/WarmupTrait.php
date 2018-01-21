<?php

namespace Reinfi\DependencyInjection\Traits;

use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Zend\Cache\Storage\StorageInterface;

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
     * @param StorageInterface         $cache
     */
    protected function warmupConfig(
        array $factoriesConfig,
        ExtractorInterface $extractor,
        ResolverServiceInterface $resolverService,
        StorageInterface $cache
    ) {
        array_walk(
            $factoriesConfig,
            function (
                $className,
                $factoryClass
            ) use ($extractor, $resolverService, $cache) {
                $this->handleService(
                    $className,
                    $factoryClass,
                    $extractor,
                    $resolverService,
                    $cache
                );
            }
        );
    }

    /**
     * @param string                   $className
     * @param string                   $factoryClass
     * @param ExtractorInterface       $extractor
     * @param ResolverServiceInterface $resolverService
     * @param StorageInterface         $cache
     */
    private function handleService(
        string $className,
        string $factoryClass,
        ExtractorInterface $extractor,
        ResolverServiceInterface $resolverService,
        StorageInterface $cache
    ) {
        if ($factoryClass === InjectionFactory::class) {
            $this->warmupInjection(
                $extractor,
                $cache,
                $className
            );

            return;
        }

        if ($factoryClass === AutoWiringFactory::class) {
            $this->warmupAutoWiring(
                $resolverService,
                $cache,
                $className
            );

            return;
        }
    }

    /**
     * @param ExtractorInterface $extractor
     * @param StorageInterface   $cache
     * @param string             $className
     */
    private function warmupInjection(
        ExtractorInterface $extractor,
        StorageInterface $cache,
        string $className
    ) {
        $injections = array_merge(
            $extractor->getPropertiesInjections($className),
            $extractor->getConstructorInjections($className)
        );

        $cache->setItem(
            $this->buildCacheKey($className),
            $injections
        );
    }

    /**
     * @param ResolverServiceInterface $resolverService
     * @param StorageInterface         $cache
     * @param string                   $className
     */
    private function warmupAutoWiring(
        ResolverServiceInterface $resolverService,
        StorageInterface $cache,
        string $className
    ) {
        $injections = $resolverService->resolve($className);

        $cache->setItem(
            $this->buildCacheKey($className),
            $injections
        );
    }
}
