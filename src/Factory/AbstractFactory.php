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
     * @param ServiceLocatorInterface $serviceLocator
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
                __CLASS__
            )
        );
    }

    /**
     * @param class-string $className
     * @param array  $injections
     *
     * @return object
     */
    protected function buildInstance(string $className, array $injections): object
    {
        $reflectionClass = new ReflectionClass($className);

        $instance = $reflectionClass->newInstanceArgs($injections);

        return $instance;
    }
}
