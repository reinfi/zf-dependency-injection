<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Exception;

/**
 * @codeCoverageIgnore
 *
 * @package Reinfi\DependencyInjection\Exception
 */
class AutoWiringNotPossibleException extends \Exception
{
    /**
     * @param \ReflectionParameter $reflParameter
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromParameterName(\ReflectionParameter $reflParameter): self
    {
        return new self(
            sprintf(
                'Could not resolve parameter %s to to a class',
                $reflParameter->getName()
            )
        );
    }

    /**
     * @param \ReflectionClass $reflClass
     * @param \ReflectionClass|null $constructedClass
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromClassName(\ReflectionClass $reflClass, ?\ReflectionClass $constructedClass): self
    {
        return new self(
            sprintf(
                'Could not resolve class %s to inject into class %s',
                $reflClass->getName(),
                $constructedClass === null ? '<unknown>' : $constructedClass->getName()
            )
        );
    }

    /**
     * @param \ReflectionParameter $reflParameter
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromMissingTypeHint(
        \ReflectionParameter $reflParameter
    ): self {
        $declaringClass = $reflParameter->getDeclaringClass();

        return new self(
            sprintf(
                'Could not resolve variable %s as it is missing a typehint to inject into class %s',
                $reflParameter->getName(),
                $declaringClass === null ? '<unknown>' : $declaringClass->getName()
            )
        );
    }

    /**
     * @param \ReflectionParameter $reflParameter
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromBuildInType(\ReflectionParameter $reflParameter): self
    {
        $declaringClass = $reflParameter->getDeclaringClass();

        return new self(
            sprintf(
                'Could not resolve variable %s as it is of a buildin type to inject into class %s',
                $reflParameter->getName(),
                $declaringClass === null ? '<unknown>' : $declaringClass->getName()
            )
        );
    }
}
