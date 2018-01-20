<?php

namespace Reinfi\DependencyInjection\Command;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Symfony\Component\Console\Command\Command;

/**
 * @package Reinfi\DependencyInjection\Command
 */
abstract class AbstractWarmupCommand extends Command
{
    use CacheKeyTrait;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('reinfi:di:cache')
            ->setDescription('Warm up the cache');
    }

    /**
     * @param ContainerInterface $container
     * @param array              $serviceConfig
     */
    protected function warmupConfig(ContainerInterface $container, array $serviceConfig)
    {
        $extractor = $container->get(ExtractorInterface::class);
        $resolverService = $container->get(ResolverService::class);
        $cache = $container->get(CacheService::class);

        $factoriesConfig = $serviceConfig['factories'];

        foreach ($factoriesConfig as $className => $factoryClass) {
            if ($factoryClass === InjectionFactory::class) {
                $injections = array_merge(
                    $extractor->getPropertiesInjections($className),
                    $extractor->getConstructorInjections($className)
                );

                $cache->setItem(
                    $this->buildCacheKey($className),
                    $injections
                );

                continue;
            }

            if ($factoryClass === AutoWiringFactory::class) {
                $injections = $resolverService->resolve($className);

                $cache->setItem(
                    $this->buildCacheKey($className),
                    $injections
                );

                continue;
            }
        }
    }
}
