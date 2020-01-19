<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Laminas\Stdlib\RequestInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class RequestResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        $reflectionClass = $parameter->getClass();
        $interfaceNames = $reflectionClass->getInterfaceNames();

        if (
            $reflectionClass->getName() !== RequestInterface::class
            && !in_array(RequestInterface::class, $interfaceNames)
        ) {
            return null;
        }

        return new AutoWiring('Request');
    }
}
