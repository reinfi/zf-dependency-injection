<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Factory
 */
class ResolverServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ResolverService
     */
    public function __invoke(ContainerInterface $container): ResolverService
    {
        /** @var Config $config */
        $config = $container->get(ModuleConfig::class);

        $resolverStack = $config->get('autowire_resolver');
        if ($resolverStack === null) {
            $resolverStack = new Config(
                [
                    ContainerResolver::class,
                    PluginManagerResolver::class,
                ]
            );
        }

        $resolverStack = array_map(
            [$container, 'get'],
            $resolverStack->toArray()
        );

        return new ResolverService($resolverStack);
    }
}