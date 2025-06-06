<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Factory;

use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use ReflectionClass;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @param class-string            $canonicalName
     * @param class-string            $requestedName
     *
     * @return object
     */
    public function createService(
        ServiceLocatorInterface $serviceLocator,
        $canonicalName = null,
        $requestedName = null
    ) {
        if (is_string($requestedName) && class_exists($requestedName)) {
            return $this($serviceLocator, $requestedName);
        }

        if (is_string($canonicalName) && class_exists($canonicalName)) {
            return $this($serviceLocator, $canonicalName);
        }

        throw new InvalidServiceException(
            sprintf(
                '%s requires that the requested name is provided on invocation; please update your tests or consuming container',
                self::class
            )
        );
    }

    /**
     * @param class-string $className
     */
    protected function buildInstance(string $className, array $injections): object
    {
        $reflectionClass = new ReflectionClass($className);

        return $reflectionClass->newInstanceArgs($injections);
    }
}
