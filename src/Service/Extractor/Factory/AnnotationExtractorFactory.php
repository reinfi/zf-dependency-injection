<?php

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor\Factory
 */
class AnnotationExtractorFactory
{
    /**
     * @return AnnotationExtractor
     */
    public function __invoke(): AnnotationExtractor
    {
        return new AnnotationExtractor(
            new AnnotationReader()
        );
    }
}