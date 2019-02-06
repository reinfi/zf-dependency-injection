<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
class ResolverService implements ResolverServiceInterface
{
    /**
     * @var ResolverInterface[]
     */
    private $resolverStack;

    /**
     * @param ResolverInterface[] $resolverStack
     */
    public function __construct(
        array $resolverStack
    ) {
        $this->resolverStack = $resolverStack;
    }

    /**
     * @param string     $className
     * @param null|array $options
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

	// Filter out constructor parameters that are already provided inside the $options array
        $parameters = array_filter(
            $constructor->getParameters(),
            function (\ReflectionParameter $parameter) use ($options) {
                return !array_key_exists($parameter->getName(), $options ?? []);
            }
        );

        return array_map([ $this, 'resolveParameter' ], $parameters);
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return InjectionInterface
     * @throws AutoWiringNotPossibleException
     */
    private function resolveParameter(
        ReflectionParameter $parameter
    ): InjectionInterface {
        foreach ($this->resolverStack as $resolver) {
            $injection = $resolver->resolve($parameter);

            if ($injection instanceof InjectionInterface) {
                return $injection;
            }
        }

        $this->handleUnresolvedParameter($parameter);
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @throws AutoWiringNotPossibleException
     */
    private function handleUnresolvedParameter(
        ReflectionParameter $parameter
    ): void {
        if (!$parameter->hasType()) {
            throw AutoWiringNotPossibleException::fromMissingTypeHint(
                $parameter
            );
        }

        if ($parameter->getType() !== null && $parameter->getType()->isBuiltin()) {
            throw AutoWiringNotPossibleException::fromBuildInType($parameter);
        }

        if ($parameter->getClass() === null) {
            throw AutoWiringNotPossibleException::fromParameterName($parameter);
        }

        throw AutoWiringNotPossibleException::fromClassName(
            $parameter->getClass(), $parameter->getDeclaringClass()
        );
    }
}
