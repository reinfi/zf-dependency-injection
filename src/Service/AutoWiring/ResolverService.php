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
     * @param ResolverInterface[] $resolverStack
     */
    public function __construct(
        private readonly array $resolverStack
    ) {
    }

    /**
     * @param class-string $className
     *
     * @return InjectionInterface[]
     */
    public function resolve(string $className, ?array $options = null): array
    {
        $reflectionClass = new ReflectionClass($className);

        $constructor = $reflectionClass->getConstructor();

        if ($constructor === null) {
            return [];
        }

        return array_map(
            fn (ReflectionParameter $reflectionParameter): InjectionInterface => $this->resolveParameter(
                $reflectionParameter,
                $options
            ),
            $constructor->getParameters()
        );
    }

    /**
     * @throws AutoWiringNotPossibleException
     */
    private function resolveParameter(
        ReflectionParameter $reflectionParameter,
        ?array $options = null
    ): InjectionInterface {
        $options = $options !== null && $options !== [] ? $options : [];

        // Don't try to resolve parameters present in the option array using reflections
        if (array_key_exists($reflectionParameter->getName(), $options)) {
            return new Value($options[$reflectionParameter->getName()]);
        }

        foreach ($this->resolverStack as $resolver) {
            $injection = $resolver->resolve($reflectionParameter);

            if ($injection instanceof InjectionInterface) {
                return $injection;
            }
        }

        throw $this->handleUnresolvedParameter($reflectionParameter);
    }

    /**
     * @throws AutoWiringNotPossibleException
     */
    private function handleUnresolvedParameter(ReflectionParameter $reflectionParameter): Throwable
    {
        $type = $reflectionParameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return AutoWiringNotPossibleException::fromMissingTypeHint($reflectionParameter);
        }

        if ($type->isBuiltin()) {
            throw AutoWiringNotPossibleException::fromBuildInType($reflectionParameter);
        }

        return AutoWiringNotPossibleException::fromClassName(
            $type->getName(),
            $reflectionParameter->getDeclaringClass()
        );
    }
}
