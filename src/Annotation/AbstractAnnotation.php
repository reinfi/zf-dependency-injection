<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Annotation
 */
abstract class AbstractAnnotation implements AnnotationInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return ContainerInterface
     */
    protected function determineContainer(ContainerInterface $container): ContainerInterface
    {
        if ($container instanceof AbstractPluginManager) {
            return $container
                ->getServiceLocator();
        }

        return $container;
    }
}
