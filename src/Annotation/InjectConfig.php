<?php

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectConfig implements AnnotationInterface
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
                ->get(ConfigService::class)
                ->resolve($this->value);
        }

        return $container->get(ConfigService::class)->resolve($this->value);
    }
}