<?php

declare(strict_types=1);

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Test\Service\Factory\Service3Factory;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\Service3;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotationConstructor;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefault;
use Reinfi\DependencyInjection\Test\Service\ServiceBuildInTypeWithDefaultUsingConstant;
use Reinfi\DependencyInjection\Test\Service\ServiceContainer;

return [
    'service_manager' => [
        'factories' => [
            Service1::class => AutoWiringFactory::class,
            Service2::class => InvokableFactory::class,
            Service3::class => Service3Factory::class,
            ServiceAnnotation::class => InjectionFactory::class,
            ServiceAnnotationConstructor::class => InjectionFactory::class,
            ServiceContainer::class => AutoWiringFactory::class,
            ServiceBuildInTypeWithDefault::class => AutoWiringFactory::class,
            ServiceBuildInTypeWithDefaultUsingConstant::class => AutoWiringFactory::class,
            'service_with_closure_as_factory' => fn (ServiceLocatorInterface $serviceLocator): stdClass => new stdClass(),
        ],
    ],
    'test' => [
        'value' => 1,
    ],
];
