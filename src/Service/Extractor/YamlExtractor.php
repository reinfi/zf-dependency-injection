<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use InvalidArgumentException;
use ReflectionClass;
use Reinfi\DependencyInjection\Exception\InjectionTypeUnknownException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
class YamlExtractor implements ExtractorInterface
{
    protected ?array $config = null;

    protected Yaml $yaml;

    protected string $filePath;

    protected string $injectionNamespace;

    public function __construct(
        Yaml $yaml,
        string $filePath,
        string $injectionNamespace
    ) {
        $this->yaml = $yaml;
        $this->filePath = $filePath;
        $this->injectionNamespace = $injectionNamespace;
    }

    public function getPropertiesInjections(string $className): array
    {
        return [];
    }

    public function getConstructorInjections(string $className): array
    {
        $config = $this->getConfig($className);

        if (count($config) === 0) {
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
        if ($reflectionClass->getConstructor() !== null) {
            $injection = $reflectionClass->newInstance($spec);

            if (! $injection instanceof InjectionInterface) {
                throw new InjectionTypeUnknownException('Invalid class of type ' . get_class($injection));
            }

            return $injection;
        }

        $injection = new $injectionClass();

        if (! $injection instanceof InjectionInterface) {
            throw new InjectionTypeUnknownException('Invalid class of type ' . get_class($injection));
        }

        foreach ($spec as $key => $value) {
            $injection->{$key} = $value;
        }

        return $injection;
    }
}
