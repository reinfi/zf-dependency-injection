<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor\Factory
 */
class ExtractorFactory
{
    public function __invoke(ContainerInterface $container): ExtractorInterface
    {
        /** @var array $config */
        $config = $container->get(ModuleConfig::class);

        /** @var ExtractorInterface $extractor */
        $extractor = $container->get(
            $config['extractor'] ?? AnnotationExtractor::class
        );

        return $extractor;
    }
}
