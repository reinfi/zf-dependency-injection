<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

/**
 * @package Reinfi\DependencyInjection
 */
class Module implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}
