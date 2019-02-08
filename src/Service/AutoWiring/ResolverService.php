<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Injection\Value;
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

        return array_map(
            function (ReflectionParameter $parameter) use ($options) {
                return $this->resolveParameter($parameter, $options);
            },
            $constructor->getParameters()
        );
    }

    /**
     * @param ReflectionParameter $parameter
     * @param null|array $options
     *
     * @return InjectionInterface
     * @throws AutoWiringNotPossibleException
     */
    private function resolveParameter(
        ReflectionParameter $parameter,
        ?array $options = null
    ): InjectionInterface {

        // Don't try to resolve parameters present in the options array using reflections
        if (array_key_exists($parameter->getName(), $options ?? [])) {
            return new Value($options[$parameter->getName()]);
        }

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
