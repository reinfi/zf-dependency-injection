<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
class AnnotationExtractor implements ExtractorInterface
{
    public function __construct(
        private readonly AnnotationReader $reader
    ) {
    }

    public function getPropertiesInjections(string $className): array
    {
        $injections = [];
        $reflection = new ReflectionClass($className);
        foreach ($reflection->getProperties() as $index => $property) {
            $reflectionProperty = new ReflectionProperty($className, $property->getName());

            $inject = $this->reader->getPropertyAnnotation($reflectionProperty, AnnotationInterface::class);

            if ($inject !== null) {
                $injections[$index] = $inject;
            }
        }

        return $injections;
    }

    public function getConstructorInjections(string $className): array
    {
        if (! in_array('__construct', get_class_methods($className), true)) {
            return [];
        }

        $injections = $this->reader->getMethodAnnotations(new ReflectionMethod($className, '__construct'));

        return array_filter(
            $injections,
            function ($annotation) {
                return $annotation instanceof AnnotationInterface;
            }
        );
    }
}
