<?php

declare(strict_types=1);

use Laminas\ServiceManager\Factory\InvokableFactory;
use Reinfi\DependencyInjection\AbstractFactory\Config\InjectConfigAbstractFactory;
use Reinfi\DependencyInjection\Command\CacheWarmupCommand;
use Reinfi\DependencyInjection\Config\Factory\ModuleConfigFactory;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\AutoWiring\Factory\LazyResolverServiceFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Factory\ResolverServiceFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\LazyResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\BuildInTypeWithDefaultResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\ContainerResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\PluginManagerResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\TranslatorResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResponseResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\ConfigService;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\AttributeExtractor;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\Extractor\Factory\AnnotationExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\Factory\ExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\Factory\YamlExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Service\Factory\AutoWiringServiceFactory;
use Reinfi\DependencyInjection\Service\Factory\CacheServiceFactory;
use Reinfi\DependencyInjection\Service\Factory\ConfigServiceFactory;
use Reinfi\DependencyInjection\Service\Factory\InjectionServiceFactory;
use Reinfi\DependencyInjection\Service\InjectionService;

return [
    'service_manager' => [
        'factories' => [
            ModuleConfig::class => ModuleConfigFactory::class,
            AnnotationExtractor::class => AnnotationExtractorFactory::class,
            AttributeExtractor::class => InvokableFactory::class,
            YamlExtractor::class => YamlExtractorFactory::class,
            InjectionService::class => InjectionServiceFactory::class,
            CacheService::class => CacheServiceFactory::class,
            ConfigService::class => ConfigServiceFactory::class,
            AutoWiringService::class => AutoWiringServiceFactory::class,
            ExtractorInterface::class => ExtractorFactory::class,
            ResolverService::class => ResolverServiceFactory::class,
            LazyResolverService::class => LazyResolverServiceFactory::class,
            ContainerResolver::class => ContainerResolverFactory::class,
            ContainerInterfaceResolver::class => InvokableFactory::class,
            PluginManagerResolver::class => PluginManagerResolverFactory::class,
            RequestResolver::class => InvokableFactory::class,
            ResponseResolver::class => InvokableFactory::class,
            TranslatorResolver::class => TranslatorResolverFactory::class,
            BuildInTypeWithDefaultResolver::class => InvokableFactory::class,

            CacheWarmupCommand::class => InvokableFactory::class,
        ],
        'abstract_factories' => [InjectConfigAbstractFactory::class],
    ],
];
