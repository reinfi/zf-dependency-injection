<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
interface ResolverInterface
{
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface;
}
