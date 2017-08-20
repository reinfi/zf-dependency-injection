<?php

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Annotation
 */
abstract class AbstractInjectPluginManager implements AnnotationInterface
{
    /**
     * @var string
     */
    const PLUGIN_MANAGER = '';

    /**
     * @var string
     */
    public $value;

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            return $container
                ->getServiceLocator()
                ->get(static::PLUGIN_MANAGER)
                ->get($this->value);
        }

        return $container->get(static::PLUGIN_MANAGER)->get($this->value);
    }
}