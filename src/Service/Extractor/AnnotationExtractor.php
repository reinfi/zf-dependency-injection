<?php

namespace Reinfi\DependencyInjection\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionMethod;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
class AnnotationExtractor implements ExtractorInterface
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * @param AnnotationReader $reader
     */
    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @inheritdoc
     */
    public function getPropertiesInjections(string $className): array
    {
        $injections = [];
        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getProperties() as $index => $property) {
            $reflectionProperty = new \ReflectionProperty(
                $className,
                $property->getName()
            );

            $inject = $this->reader->getPropertyAnnotation(
                $reflectionProperty,
                AnnotationInterface::class
            );

            if (null !== $inject) {
                $injections[$index] = $inject;
            }
        }

        return $injections;
    }

    /**
     * @inheritDoc
     */
    public function getConstructorInjections(string $className): array
    {
        if (!in_array('__construct', get_class_methods($className))) {
            return [];
        }

        $injection = $this->reader->getMethodAnnotation(
            new ReflectionMethod($className, '__construct'),
            AnnotationInterface::class
        );

        if ($injection === null) {
            return [];
        }

        return [$injection];
    }
}
