<?php
declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory;

use Interop\Container\ContainerInterface;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class FallbackAutoWiringFactory
 *
 * Note: Please DO NOT use this factory for everything.
 * If you're implementing new classes, please write a concrete factory.
 *
 * @package Reinfi\DependencyInjection\AbstractFactory
 * @codeCoverageIgnore
 */
class FallbackAutoWiringFactory implements AbstractFactoryInterface
{

    /**
     * @inheritdoc
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return class_exists($requestedName);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return (new AutoWiringFactory())($container, $requestedName, $options);
    }

}