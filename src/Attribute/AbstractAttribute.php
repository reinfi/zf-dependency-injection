<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\AbstractPluginManager;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
abstract class AbstractAttribute implements InjectionInterface
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
