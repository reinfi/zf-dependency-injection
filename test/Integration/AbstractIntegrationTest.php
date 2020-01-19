<?php

namespace Reinfi\DependencyInjection\Test\Integration;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Annotation\InjectConstant;
use Reinfi\DependencyInjection\Annotation\InjectParent;
use Laminas\Mvc\Service\ServiceManagerConfig;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Stdlib\ArrayUtils;

/**
 * @package Reinfi\DependencyInjection\Test\Integration
 */
abstract class AbstractIntegrationTest extends TestCase
{
    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Needed to find annotations through reader
        class_exists(Inject::class);
        class_exists(InjectParent::class);
        class_exists(InjectConfig::class);
        class_exists(InjectConstant::class);
    }


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

        $reflectionClass = new \ReflectionClass(ServiceManager::class);
        $reflConstructor = $reflectionClass->getConstructor();
        $constructorParameter = $reflConstructor->getParameters()[0];

        if ($constructorParameter->getType()->isBuiltin()) {
            $container = new ServiceManager($services);
        } else {
            $container = new ServiceManager(new ServiceManagerConfig($services));
        }

        $container->setService('config', $config);

        return $container;
    }
}
