<?php

namespace Reinfi\DependencyInjection\Exception;

/**
 * @package Reinfi\DependencyInjection\Exception
 */
class AutoWiringNotPossibleException extends \Exception
{
    /**
     * @param \ReflectionClass $reflClass
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromClassName(\ReflectionClass $reflClass): self
    {
        return new self(
            sprintf(
                'Could not resolve class %s',
                $reflClass->getName()
            )
        );
    }

    /**
     * @param \ReflectionParameter $reflParameter
     *
     * @return AutoWiringNotPossibleException
     */
    public static function fromMissingTypeHint(\ReflectionParameter $reflParameter): self
    {
        return new self(
            sprintf(
                "Could not resolve variable %s as it is missing a typehint",
                $reflParameter->getName()
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
        return new self(
            sprintf(
                "Could not resolve variable %s as it is of a buildin type",
                $reflParameter->getName()
            )
        );
    }
}