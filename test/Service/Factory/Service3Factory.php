<?php

namespace Reinfi\DependencyInjection\Test\Service\Factory;

use Interop\Container\ContainerInterface;
use Reinfi\DependencyInjection\Test\Service\Service3;

/**
 * @package Reinfi\DependencyInjection\Test\Service\Factory
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
