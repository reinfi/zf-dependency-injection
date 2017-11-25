<?php

$modules = [
    \Reinfi\DependencyInjection\Module::class,
];

if (class_exists('Zend\Router\Module')) {
    $modules[] = 'Zend\Router\Module';
}

return [
    'modules'                 => $modules,
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/config.php',
        ],
    ],
];