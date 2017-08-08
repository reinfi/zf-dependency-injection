<?php

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectInputFilter implements AnnotationInterface
{
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
                ->get('InputFilterManager')
                ->get($this->value);
        }

        return $container->get('InputFilterManager')->get($this->value);
    }
}