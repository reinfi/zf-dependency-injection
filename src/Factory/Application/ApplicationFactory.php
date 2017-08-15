<?php

namespace Reinfi\DependencyInjection\Factory\Application;

use Reinfi\DependencyInjection\OptimizedServiceManager;
use Reinfi\DependencyInjection\Service\Optimizer\OptimizerService;
use Zend\Mvc\Application;
use Zend\ServiceManager\DelegatorFactoryInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager as ZendServiceManager;

/**
 * @package Reinfi\DependencyInjection\Factory\Application
 */
class ApplicationFactory implements FactoryInterface, DelegatorFactoryInterface
{
    /**
     * Create the Application service (v3)
     *
     * Creates a Zend\Mvc\Application service, passing it the configuration
     * service and the service manager instance.
     *
     * @param  ZendServiceManager $container
     * @param  string             $name
     * @param  null|array         $options
     *
     * @return Application
     */
    public function __invoke(
        ZendServiceManager $container,
        $name,
        array $options = null
    ) {
        return new Application(
            $container->get('config'),
            new OptimizedServiceManager($container),
            $container->get('EventManager'),
            $container->get('Request'),
            $container->get('Response')
        );
    }

    /**
     * Create the Application service (v2)
     *
     * Proxies to __invoke().
     *
     * @param ServiceLocatorInterface|ZendServiceManager $container
     *
     * @return Application
     */
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, Application::class);
    }

    /**
     * @param ServiceLocatorInterface|ZendServiceManager $serviceLocator
     * @param string                                     $name
     * @param string                                     $requestedName
     * @param callable                                   $callback
     *
     * @return Application
     */
    public function createDelegatorWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName,
        $callback
    ) {
        if (class_exists(OptimizerService::SERVICE_MANAGER_FQCN)) {
            return $this($serviceLocator, $requestedName);
        }

        return $callback();
    }
}