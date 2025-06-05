<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

class AttributeExtractor implements ExtractorInterface
{
    public function getPropertiesInjections(string $className): array
    {
        $injections = [];
        $reflectionClass = new ReflectionClass($className);
        foreach ($reflectionClass->getProperties() as $index => $property) {
            $reflectionProperty = new ReflectionProperty($className, $property->getName());

            $attributes = $reflectionProperty->getAttributes();

            foreach ($attributes as $attribute) {
                $injection = $this->getInjectionFromAttribute($attribute);
                if (! $injection instanceof InjectionInterface) {
                    continue;
                }

                $injections[$index] = $injection;

                // Only one attribute is supported at a property.
                break;
            }
        }

        return $injections;
    }

    public function getConstructorInjections(string $className): array
    {
        $injections = [];
        $reflectionClass = new ReflectionClass($className);

        $reflectionConstructor = $reflectionClass->getConstructor();

        if ($reflectionConstructor === null) {
            return $injections;
        }

        $attributes = $reflectionConstructor->getAttributes();

        foreach ($attributes as $attribute) {
            $injection = $this->getInjectionFromAttribute($attribute);
            if (! $injection instanceof InjectionInterface) {
                continue;
            }

            $injections[] = $injection;
        }

        return $injections;
    }

    private function getInjectionFromAttribute(ReflectionAttribute $reflectionAttribute): ?InjectionInterface
    {
        $attributeName = $reflectionAttribute->getName();
        if (! is_subclass_of($attributeName, InjectionInterface::class)) {
            return null;
        }

        $instance = $reflectionAttribute->newInstance();
        if (! $instance instanceof InjectionInterface) {
            return null;
        }

        return $instance;
    }
}
