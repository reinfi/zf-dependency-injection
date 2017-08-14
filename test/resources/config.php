<?php

return [
    'service_manager' => [
        'factories' => [
            \Reinfi\DependencyInjection\Service\Service1::class          => \Reinfi\DependencyInjection\Factory\AutoWiringFactory::class,
            \Reinfi\DependencyInjection\Service\Service2::class          => \Zend\ServiceManager\Factory\InvokableFactory::class,
            \Reinfi\DependencyInjection\Service\ServiceAnnotation::class => \Zend\ServiceManager\Factory\InvokableFactory::class,
        ],
    ],
];