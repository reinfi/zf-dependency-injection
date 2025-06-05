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
    public static function fromClassName(string $className, ?ReflectionClass $reflectionClass): self
    {
        return new self(
            sprintf(
                'Could not resolve class %s to inject into class %s',
                $className,
                $reflectionClass instanceof ReflectionClass ? $reflectionClass->getName() : '<unknown>'
            )
        );
    }

    public static function fromMissingTypeHint(ReflectionParameter $reflectionParameter): self
    {
        $declaringClass = $reflectionParameter->getDeclaringClass();

        return new self(
            sprintf(
                'Could not resolve variable %s as it is missing a typehint to inject into class %s',
                $reflectionParameter->getName(),
                $declaringClass === null ? '<unknown>' : $declaringClass->getName()
            )
        );
    }

    public static function fromBuildInType(ReflectionParameter $reflectionParameter): self
    {
        $declaringClass = $reflectionParameter->getDeclaringClass();

        return new self(
            sprintf(
                'Could not resolve variable %s as it is of a buildin type to inject into class %s',
                $reflectionParameter->getName(),
                $declaringClass === null ? '<unknown>' : $declaringClass->getName()
            )
        );
    }
}
