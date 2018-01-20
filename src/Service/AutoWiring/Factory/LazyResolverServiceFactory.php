<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;

/**
 * Class LazyResolverServiceFactory
 *
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Factory
 * @author Martin Rintelen <martin.rintelen@check24.de>
 */
class LazyResolverServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return LazyResolverService
     */
    public function __invoke(ContainerInterface $container): LazyResolverService
    {
        return new LazyResolverService($container);
    }
}
