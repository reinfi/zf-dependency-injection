<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Exception;

use Exception;
use ReflectionClass;
use ReflectionParameter;

/**
 * @codeCoverageIgnore
 *
 * @package Reinfi\DependencyInjection\Exception
 */
class AutoWiringNotPossibleException extends Exception
{
    public static function fromClassName(string $className, ?ReflectionClass $constructedClass): self
    {
        return new self(
            sprintf(
                'Could not resolve class %s to inject into class %s',
                $className,
                $constructedClass === null ? '<unknown>' : $constructedClass->getName()
            )
        );
    }

    public static function fromMissingTypeHint(ReflectionParameter $reflParameter): self
    {
        $declaringClass = $reflParameter->getDeclaringClass();

        return new self(
            sprintf(
                'Could not resolve variable %s as it is missing a typehint to inject into class %s',
                $reflParameter->getName(),
                $declaringClass === null ? '<unknown>' : $declaringClass->getName()
            )
        );
    }

    public static function fromBuildInType(ReflectionParameter $reflParameter): self
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
