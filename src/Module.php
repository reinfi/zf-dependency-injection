<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection;

/**
 * @package Reinfi\DependencyInjection
 */
class Module
{
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}
