<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Exception;

/**
 * @package Reinfi\DependencyInjection\Exception
 */
class AutoWiringNotPossibleException extends \Exception
{
    /**
     * @param \ReflectionClass $reflClass
     *
     * @param \ReflectionClass $constructedClass
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromClassName(\ReflectionClass $reflClass, \ReflectionClass $constructedClass): self
    {
        return new self(
            sprintf(
                'Could not resolve class %s to inject into class %s',
                $reflClass->getName(),
                $constructedClass->getName()
            )
        );
    }

    /**
     * @param \ReflectionParameter $reflParameter
     *
     * @param \ReflectionClass     $constructedClass
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromMissingTypeHint(
        \ReflectionParameter $reflParameter,
        \ReflectionClass $constructedClass
    ): self {
        return new self(
            sprintf(
                'Could not resolve variable %s as it is missing a typehint to inject into class %s',
                $reflParameter->getName(),
                $constructedClass->getName()
            )
        );
    }

    /**
     * @param \ReflectionParameter $reflParameter
     *
     * @param \ReflectionClass     $constructedClass
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromBuildInType(
        \ReflectionParameter $reflParameter,
        \ReflectionClass $constructedClass
    ): self {
        return new self(
            sprintf(
                'Could not resolve variable %s as it is of a buildin type to inject into class %s',
                $reflParameter->getName(),
                $constructedClass->getName()
            )
        );
    }
}
