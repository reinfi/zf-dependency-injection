<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
interface ResolverServiceInterface
{
    /**
     * @param string $className
     *
     * @return InjectionInterface[]
     */
    public function resolve(string $className): array;
}
