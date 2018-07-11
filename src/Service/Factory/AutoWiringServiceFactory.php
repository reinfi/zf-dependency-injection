<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Service\CacheService;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class AutoWiringServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return AutoWiringService
     */
    public function __invoke(ContainerInterface $container): AutoWiringService
    {
        /** @var LazyResolverService $resolverService */
        $resolverService = $container->get(LazyResolverService::class);

        /** @var CacheService $cache */
        $cache = $container->get(CacheService::class);

        return new AutoWiringService($resolverService, $cache);
    }
}
