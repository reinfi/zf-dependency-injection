<?php

namespace Reinfi\DependencyInjection\Integration;

use PHPUnit\Framework\TestCase;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;

/**
 * @package Reinfi\DependencyInjection\Integration
 */
abstract class AbstractIntegrationTest extends TestCase
{
    /**
     * @param array $config
     *
     * @return ServiceManager
     */
    protected function getServiceManager(array $config = []): ServiceManager
    {
        $moduleServices = require __DIR__ . '/../../config/module.config.php';
        $services = ArrayUtils::merge(
            $moduleServices['service_manager'] ?? [],
            $config['service_manager'] ?? []
        );
        $smConfig = new ServiceManagerConfig($services);
        $container = new ServiceManager($smConfig);

        $container->setService('config', $config);

        return $container;
    }
}