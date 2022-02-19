<?php

namespace Reinfi\DependencyInjection\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;

trait ContainerTrait
{
    protected function determineContainer(ContainerInterface $container): ContainerInterface
    {
        if ($container instanceof AbstractPluginManager) {
            return $container
                ->getServiceLocator();
        }

        return $container;
    }
}
