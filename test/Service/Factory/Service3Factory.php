<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Test\Service\Service3;

/**
 * @package Reinfi\DependencyInjection\Test\Service\Factory
 */
class Service3Factory
{
    public function __invoke(ContainerInterface $container): Service3
    {
        return new Service3();
    }
}
