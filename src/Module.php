<?php

namespace Reinfi\DependencyInjection;

use Reinfi\DependencyInjection\Service\Optimizer\OptimizerService;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * @package Reinfi\DependencyInjection
 */
class Module implements ConfigProviderInterface, InitProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        $manager->getEventManager()->attach(
            ModuleEvent::EVENT_LOAD_MODULES_POST,
            [
                $this,
                'replaceServiceManager',
            ],
            PHP_INT_MAX
        );
    }

    /**
     * @param ModuleEvent $event
     */
    public function replaceServiceManager(ModuleEvent $event)
    {
        $container = $event->getParam('ServiceManager');

        if (class_exists(OptimizerService::SERVICE_MANAGER_FQCN)) {
            $event->setParam('ServiceManager', new OptimizedServiceManager($container));
        }
    }
}