<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use Reinfi\DependencyInjection\Exception\InjectionTypeUnknownException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 * @deprecated Use attributes or autowiring instead. This class will be removed in version 8.0.0.
 */
class YamlExtractor implements ExtractorInterface
{
    private ?array $config = null;

    public function __construct(
        private readonly Yaml $yaml,
        private readonly string $filePath,
        private readonly string $injectionNamespace
    ) {
    }

    public function getPropertiesInjections(string $className): array
    {
        return [];
    }

    public function getConstructorInjections(string $className): array
    {
        $config = $this->getConfig($className);

        if ($config === []) {
            return [];
        }

        $injections = [];
        foreach ($config as $spec) {
            $type = $spec['type'] ?? false;

            if ($type === false) {
                throw new InvalidArgumentException('Missing property type for class ' . $className);
            }

            unset($spec['type']);

            $injections[] = $this->buildInjection($type, $spec);
        }

        return $injections;
    }

    private function getConfig(string $className): array
    {
        if (! is_array($this->config)) {
            $fileContents = file_get_contents($this->filePath);

            if ($fileContents === false) {
                throw new RuntimeException('could not read config from path ' . $this->filePath);
            }

            $parsedFile = $this->yaml::parse($fileContents);
            assert(is_array($parsedFile));
            $this->config = $parsedFile;
        }

        return $this->config[$className] ?? [];
    }

    /**
     * @throws InjectionTypeUnknownException
     */
    private function buildInjection(string $type, array $spec): InjectionInterface
    {
        $injectionClass = $this->injectionNamespace . '\\' . $type;

        if (! class_exists($injectionClass)) {
            throw new InjectionTypeUnknownException('Invalid injection type ' . $type);
        }

        $reflectionClass = new ReflectionClass($injectionClass);
        if ($reflectionClass->getConstructor() instanceof ReflectionMethod) {
            $injection = $reflectionClass->newInstance($spec);

            if (! $injection instanceof InjectionInterface) {
                throw new InjectionTypeUnknownException('Invalid class of type ' . $injection::class);
            }

            return $injection;
        }

        $injection = new $injectionClass();

        if (! $injection instanceof InjectionInterface) {
            throw new InjectionTypeUnknownException('Invalid class of type ' . $injection::class);
        }

        foreach ($spec as $key => $value) {
            $injection->{$key} = $value;
        }

        return $injection;
    }
}
