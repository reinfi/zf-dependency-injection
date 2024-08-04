<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
interface InjectionInterface
{
    public function __invoke(ContainerInterface $container): mixed;
}
