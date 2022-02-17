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

        $extractors = [
            $this->extractorByConfig($container, $config)
        ];

        if (version_compare(PHP_VERSION, '8.0.0') >= 0) {
            $extractors[] = $container->get(AttributeExtractor::class);
        }

        return new ExtractorChain($extractors);
    }

    private function extractorByConfig(ContainerInterface $container, array $config): ExtractorInterface
    {
        $extractor = $container->get($config['extractor'] ?? AnnotationExtractor::class);

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
