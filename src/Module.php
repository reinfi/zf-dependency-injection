<?php

namespace Reinfi\DependencyInjection;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * @package Reinfi\DependencyInjection
 */
class Module implements ConfigProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}