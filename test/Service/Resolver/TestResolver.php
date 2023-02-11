<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Service\Resolver
 */
class TestResolver implements ResolverInterface
{
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        return null;
    }
}
