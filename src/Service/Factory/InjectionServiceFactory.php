<?php

namespace Reinfi\DependencyInjection\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class InjectionServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return InjectionService
     */
    public function __invoke(ContainerInterface $container): InjectionService
    {
        /** @var Config $config */
        $config = $container->get(ModuleConfig::class);

        /** @var ExtractorInterface $extractor */
        $extractor = $container->get(ExtractorInterface::class);

        /** @var CacheService $cache */
        $cache = $container->get(CacheService::class);

        return new InjectionService($extractor, $cache);
    }
}