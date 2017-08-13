<?php


namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
interface ResolverInterface
{
    /**
     * @param ReflectionParameter $parameter
     *
     * @return InjectionInterface
     */
    public function resolve(ReflectionParameter $parameter);
}