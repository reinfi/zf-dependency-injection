<?php

namespace Reinfi\DependencyInjection\Service\Factory;

use Interop\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\Service3;

/**
 * @package Reinfi\DependencyInjection\Service\Factory
 */
class Service3Factory
{
    /**
     * @param ContainerInterface $container
     *
     * @return Service3
     */
    public function __invoke(ContainerInterface $container): Service3
    {
        return new Service3();
    }
}