<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Factory;

use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
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
     * @param string $className
     * @param array  $injections
     *
     * @return object
     */
    protected function buildInstance(string $className, array $injections)
    {
        $reflClass = new \ReflectionClass($className);

        $instance = $reflClass->newInstanceArgs($injections);

        return $instance;
    }
}
