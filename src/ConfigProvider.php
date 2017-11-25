<?php

namespace Reinfi\DependencyInjection;

/**
 * @package Reinfi\DependencyInjection
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        $config = (new Module())->getConfig();

        return [
            'dependencies' => $config['service_manager'],
        ];
    }
}