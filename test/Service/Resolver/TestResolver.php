<?php

namespace Reinfi\DependencyInjection\Service\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;

/**
 * @package Reinfi\DependencyInjection\Service\Resolver
 */
class TestResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter)
    {
        return null;
    }
}