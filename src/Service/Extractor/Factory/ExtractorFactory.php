<?php

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor\Factory
 */
class ExtractorFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ExtractorInterface
     */
    public function __invoke(ContainerInterface $container): ExtractorInterface
    {
        /** @var Config $config */
        $config = $container->get(ModuleConfig::class);

        /** @var ExtractorInterface $extractor */
        $extractor = $container->get($config->get('extractor', AnnotationExtractor::class));

        return $extractor;
    }
}