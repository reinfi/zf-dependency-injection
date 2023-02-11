<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Injection\Value;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;
use Throwable;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
class ResolverService implements ResolverServiceInterface
{
    /**
     * @var ResolverInterface[]
     */
    private array $resolverStack;

    /**
     * @param ResolverInterface[] $resolverStack
     */
    public function __construct(
        array $resolverStack
    ) {
        $this->resolverStack = $resolverStack;
    }

    /**
     * @param class-string $className
     *
     * @return InjectionInterface[]
     */
    public function resolve(string $className, ?array $options = null): array
    {
        $reflClass = new ReflectionClass($className);

        $constructor = $reflClass->getConstructor();

        if ($constructor === null) {
            return [];
        }

        return array_map(
            function (ReflectionParameter $parameter) use ($options) {
                return $this->resolveParameter($parameter, $options);
            },
            $constructor->getParameters()
        );
    }

    /**
     * @throws AutoWiringNotPossibleException
     */
    private function resolveParameter(
        ReflectionParameter $parameter,
        ?array $options = null
    ): InjectionInterface {
        $options = $options ?: [];

        // Don't try to resolve parameters present in the options array using reflections
        if (array_key_exists($parameter->getName(), $options)) {
            return new Value($options[$parameter->getName()]);
        }

        foreach ($this->resolverStack as $resolver) {
            $injection = $resolver->resolve($parameter);

            if ($injection instanceof InjectionInterface) {
                return $injection;
            }
        }

        throw $this->handleUnresolvedParameter($parameter);
    }

    /**
     * @throws AutoWiringNotPossibleException
     */
    private function handleUnresolvedParameter(
        ReflectionParameter $parameter
    ): Throwable {
        $type = $parameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return AutoWiringNotPossibleException::fromMissingTypeHint(
                $parameter
            );
        }

        if ($type->isBuiltin()) {
            throw AutoWiringNotPossibleException::fromBuildInType($parameter);
        }

        return AutoWiringNotPossibleException::fromClassName(
            $type->getName(),
            $parameter->getDeclaringClass()
        );
    }
}
