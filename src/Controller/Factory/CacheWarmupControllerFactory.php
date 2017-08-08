<?php

namespace Reinfi\DependencyInjection\Controller\Factory;

use Reinfi\DependencyInjection\Controller\CacheWarmupController;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Zend\Config\Config;
use Zend\Mvc\Controller\ControllerManager;

/**
 * @package Reinfi\DependencyInjection\Controller\Factory
 */
class CacheWarmupControllerFactory
{
    /**
     * @param ControllerManager $controllerManager
     *
     * @return CacheWarmupController
     */
    public function __invoke(ControllerManager $controllerManager): CacheWarmupController
    {
        $container = $controllerManager->getServiceLocator();

        $serviceManagerConfig = $container->get('config')['service_manager'];

        /** @var ExtractorInterface $extractor */
        $extractor = $container->get(ExtractorInterface::class);

        /** @var CacheService $cache */
        $cache = $container->get(CacheService::class);

        return new CacheWarmupController(
            $serviceManagerConfig,
            $extractor,
            $cache
        );
    }
}