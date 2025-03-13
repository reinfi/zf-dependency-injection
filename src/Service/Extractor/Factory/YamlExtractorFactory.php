<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor\Factory
 * @deprecated Use attributes or autowiring instead. This class will be removed in version 8.0.0.
 */
class YamlExtractorFactory
{
    public function __invoke(ContainerInterface $container): YamlExtractor
    {
        $yaml = new Yaml();

        /** @var array $config */
        $config = $container->get(ModuleConfig::class);

        $reflClass = new ReflectionClass(AnnotationInterface::class);

        return new YamlExtractor($yaml, $config['extractor_options']['file'], $reflClass->getNamespaceName());
    }
}
