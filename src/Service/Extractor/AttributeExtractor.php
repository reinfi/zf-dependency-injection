<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use ReflectionClass;
use ReflectionProperty;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

class AttributeExtractor implements ExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function getPropertiesInjections(string $className): array
    {
        $injections = [];
        $reflection = new ReflectionClass($className);
        foreach ($reflection->getProperties() as $index => $property) {
            $reflectionProperty = new ReflectionProperty(
                $className,
                $property->getName()
            );

            $attributes = $reflectionProperty->getAttributes();

            foreach ($attributes as $attribute) {
                $attributeName = $attribute->getName();
                if (!is_subclass_of($attributeName, InjectionInterface::class)) {
                    continue;
                }

                $instance = $attribute->newInstance();
                if (!$instance instanceof InjectionInterface) {
                    continue;
                }

                $injections[$index] = $instance;

                // Only one attribute is supported at a property.
                break;
            }
        }

        return $injections;
    }

    /**
     * @inheritDoc
     */
    public function getConstructorInjections(string $className): array
    {
        $injections = [];
        $reflection = new ReflectionClass($className);

        $reflectionConstructor = $reflection->getConstructor();

        if ($reflectionConstructor === null) {
            return $injections;
        }

        $attributes = $reflectionConstructor->getAttributes();

        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            if (!is_subclass_of($attributeName, InjectionInterface::class)) {
                continue;
            }

            $instance = $attribute->newInstance();
            if (!$instance instanceof InjectionInterface) {
                continue;
            }

            $injections[] = $instance;
        }

        return $injections;
    }
}
