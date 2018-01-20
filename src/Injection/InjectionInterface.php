<?php

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * Interface InjectionInterface
 *
 * @package Reinfi\DependencyInjection\Injection
 */
interface InjectionInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container);
}
