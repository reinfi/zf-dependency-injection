<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiringContainer implements InjectionInterface
{
    public function __invoke(ContainerInterface $container): ContainerInterface
    {
        return $container;
    }
}
