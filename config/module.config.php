<?php

return [
    'service_manager' => [
        'factories'          => [
            \Reinfi\DependencyInjection\Config\ModuleConfig::class                                    => \Reinfi\DependencyInjection\Config\Factory\ModuleConfigFactory::class,
            \Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor::class                  => \Reinfi\DependencyInjection\Service\Extractor\Factory\AnnotationExtractorFactory::class,
            \Reinfi\DependencyInjection\Service\Extractor\YamlExtractor::class                        => \Reinfi\DependencyInjection\Service\Extractor\Factory\YamlExtractorFactory::class,
            \Reinfi\DependencyInjection\Service\InjectionService::class                               => \Reinfi\DependencyInjection\Service\Factory\InjectionServiceFactory::class,
            \Reinfi\DependencyInjection\Service\CacheService::class                                   => \Reinfi\DependencyInjection\Service\Factory\CacheServiceFactory::class,
            \Reinfi\DependencyInjection\Service\ConfigService::class                                  => \Reinfi\DependencyInjection\Service\Factory\ConfigServiceFactory::class,
            \Reinfi\DependencyInjection\Service\AutoWiringService::class                              => \Reinfi\DependencyInjection\Service\Factory\AutoWiringServiceFactory::class,
            \Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface::class                   => \Reinfi\DependencyInjection\Service\Extractor\Factory\ExtractorFactory::class,
            \Reinfi\DependencyInjection\Service\AutoWiring\ResolverService::class                     => \Reinfi\DependencyInjection\Service\AutoWiring\Factory\ResolverServiceFactory::class,
            \Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver::class          => \Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\ContainerResolverFactory::class,
            \Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
            \Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver::class      => \Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\PluginManagerResolverFactory::class,
            \Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver::class            => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
        'abstract_factories' => [
            \Reinfi\DependencyInjection\AbstractFactory\Config\InjectConfigAbstractFactory::class,
        ],
    ],
    'console'         => [
        'router' => [
            'routes' => [
                'reinfi-di-cache-warmup' => [
                    'options' => [
                        'route'    => 'reinfi:di cache warmup',
                        'defaults' => [
                            'controller' => \Reinfi\DependencyInjection\Controller\CacheWarmupController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            \Reinfi\DependencyInjection\Controller\CacheWarmupController::class => \Reinfi\DependencyInjection\Controller\Factory\CacheWarmupControllerFactory::class,
        ],
    ],
];