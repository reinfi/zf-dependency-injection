<?php

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiringContainer implements InjectionInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        return $container;
    }
}
