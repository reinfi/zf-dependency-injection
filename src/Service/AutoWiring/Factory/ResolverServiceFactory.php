<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
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

        $resolverStackConfig = $this->getResolverStack($config);

        $resolverStack = array_map(
            [$container, 'get'],
            $resolverStackConfig->toArray()
        );

        return new ResolverService($resolverStack);
    }

    /**
     * @param Config $config
     *
     * @return Config
     */
    protected function getResolverStack(Config $config): Config
    {
        $defaultResolverStackConfig = new Config(
            [
                ContainerResolver::class,
                PluginManagerResolver::class,
                ContainerInterfaceResolver::class,
                RequestResolver::class,
            ]
        );

        /** @var Config $resolverStackConfig */
        $resolverStackConfig = $config->get('autowire_resolver');
        if ($resolverStackConfig === null) {
            return $defaultResolverStackConfig;
        }

        $resolverStackConfig = $defaultResolverStackConfig->merge(
            $resolverStackConfig
        );

        return $resolverStackConfig;
    }
}