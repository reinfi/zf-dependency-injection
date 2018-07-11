<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectParent implements AnnotationInterface
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
            $container = $container->getServiceLocator();
        }

        return $container->get($this->value);
    }
}
