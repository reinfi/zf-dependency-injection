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

        $extractors = $this->extractorsFromConfig($container, $config);

        if (class_exists('Doctrine\Common\Annotations\AnnotationReader')) {
            $extractors[] = $container->get(AnnotationExtractor::class);
        }

        $extractors[] = $container->get(AttributeExtractor::class);

        return new ExtractorChain($extractors);
    }

    /**
     * @return ExtractorInterface[]
     */
    private function extractorsFromConfig(
        ContainerInterface $container,
        array $config
    ): array {
        $extractorConfiguration = $config['extractor'] ?? null;

        if ($extractorConfiguration === null) {
            return [];
        }

        if (! is_string($extractorConfiguration) && ! is_array($extractorConfiguration)) {
            throw new InvalidArgumentException(
                'Configuration property "extractor" must be of either string or array of strings'
            );
        }

        if (is_string($extractorConfiguration)) {
            $extractorConfiguration = [$extractorConfiguration];
        }

        return array_map(
            function (string $extractorClassName) use ($container): ExtractorInterface {
                $extractor = $container->get($extractorClassName);

                if ($extractor instanceof ExtractorInterface) {
                    return $extractor;
                }

                throw new InvalidArgumentException(
                    sprintf(
                        'Configuration property "extractor" must be of type %s',
                        ExtractorInterface::class
                    )
                );
            },
            $extractorConfiguration
        );
    }
}
