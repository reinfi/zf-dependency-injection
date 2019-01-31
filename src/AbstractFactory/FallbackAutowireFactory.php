<?php
declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class FallbackAutowireFactory
 *
 * Note: Please DO NOT use this factory for everything.
 * If you're implementing new classes, please write a concrete factory.
 *
 * @package Reinfi\DependencyInjection\AbstractFactory
 */
class FallbackAutowireFactory implements AbstractFactoryInterface
{

    /**
     * @var array
     */
    private static $reflectionCache = [];

    /**
     * @inheritdoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return class_exists($requestedName);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $reflection = static::$reflectionCache[$requestedName] ?? new \ReflectionClass($requestedName);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $requestedName();
        }

        $parameters = $constructor->getParameters();
        $arguments = $this->resolveParameters($container, $options ?? [], $parameters);

        return new $requestedName(...$arguments);
    }

    /**
     * Try to resolve a list of parameters
     *
     * @param ContainerInterface $container
     * @param array $options
     * @param \ReflectionParameter[] $parameters
     * @return mixed[]
     */
    private function resolveParameters(ContainerInterface $container, array $options, array $parameters): array
    {
        $arguments = [];

        foreach ($parameters as $parameter) {
            if (isset($options[$parameter->getName()])) {
                $arguments[] = $options[$parameter->getName()];
                continue;
            }

            if (!$parameter->hasType()) {
                continue;
            }

            /** @var \ReflectionType $type */
            $type = $parameter->getType();

            if ($type->isBuiltin()) {
                continue;
            }

            $arguments[] = $container->get($type->getName());
        }

        return $arguments;
    }

}