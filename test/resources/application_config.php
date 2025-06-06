<?php

declare(strict_types=1);

use Reinfi\DependencyInjection\Module;

$modules = [Module::class];

if (class_exists('Laminas\Router\Module')) {
    $modules[] = 'Laminas\Router\Module';
}

return [
    'modules' => $modules,
    'module_listener_options' => [
        'config_glob_paths' => [__DIR__ . '/config.php'],
    ],
];
