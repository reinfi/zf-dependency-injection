<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceLocatorInterface;

return [
    'service_manager' => [
        'factories' => [
            \Reinfi\DependencyInjection\Test\Service\Service1::class => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
            \Reinfi\DependencyInjection\Test\Service\Service2::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Reinfi\DependencyInjection\Test\Service\Service3::class => \Reinfi\DependencyInjection\Test\Service\Factory\Service3Factory::class,
            \Reinfi\DependencyInjection\Test\Service\ServiceAnnotation::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
            \Reinfi\DependencyInjection\Test\Service\ServiceAnnotationConstructor::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
            \Reinfi\DependencyInjection\Test\Service\ServiceContainer::class => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
            \Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefault::class => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
            \Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefaultUsingConstant::class => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
            'service_with_closure_as_factory' => function (
                ServiceLocatorInterface $locator
            ) {
                return new \stdClass();
            },
        ],
    ],
    'test' => [
        'value' => 1,
    ],
];
