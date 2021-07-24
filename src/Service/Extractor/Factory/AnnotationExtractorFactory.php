<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor\Factory
 */
class AnnotationExtractorFactory
{
    public function __invoke(): AnnotationExtractor
    {
        return new AnnotationExtractor(
            new AnnotationReader()
        );
    }
}
