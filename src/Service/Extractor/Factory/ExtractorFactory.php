<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\AttributeExtractor;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorChain;
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

        $extractors = [];

        $extractorFromConfig = $this->extractorFromConfig($container, $config);

        if ($extractorFromConfig instanceof ExtractorInterface) {
            $extractors[] = $extractorFromConfig;
        }

        if (class_exists('Doctrine\Common\Annotations\AnnotationReader')) {
            $extractors[] = $container->get(AnnotationExtractor::class);
        }

        if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
            $extractors[] = $container->get(AttributeExtractor::class);
        }

        return new ExtractorChain($extractors);
    }

    private function extractorFromConfig(
        ContainerInterface $container,
        array $config
    ): ?ExtractorInterface {
        $extractorConfiguration = $config['extractor'] ?? null;

        if ($extractorConfiguration === null) {
            return null;
        }

        $extractor = $container->get($extractorConfiguration);

        if ($extractor instanceof ExtractorInterface) {
            return $extractor;
        }

        throw new InvalidArgumentException(
            sprintf(
                'Configuration property "extractor" must be of type %s',
                ExtractorInterface::class
            )
        );
    }
}
