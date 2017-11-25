<?php

return [
    'modules'                 => [
        \Zend\Router\Module::class,
        \Reinfi\DependencyInjection\Module::class,
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/config.php',
        ],
    ],
];