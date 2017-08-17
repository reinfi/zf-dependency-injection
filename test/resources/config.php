<?php

return [
    'service_manager' => [
        'factories' => [
            \Reinfi\DependencyInjection\Service\Service1::class          => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
            \Reinfi\DependencyInjection\Service\Service2::class          => \Zend\ServiceManager\Factory\InvokableFactory::class,
            \Reinfi\DependencyInjection\Service\Service3::class          => \Reinfi\DependencyInjection\Service\Factory\Service3Factory::class,
            \Reinfi\DependencyInjection\Service\ServiceAnnotation::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
            \Reinfi\DependencyInjection\Service\ServiceContainer::class  => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
        ],
    ],
];