<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Factory
 */
class LazyResolverServiceFactory
{
    public function __invoke(ContainerInterface $container): LazyResolverService
    {
        return new LazyResolverService($container);
    }
}
