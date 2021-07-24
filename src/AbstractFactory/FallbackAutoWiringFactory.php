<?php
declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;

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
     * @param ContainerInterface $container
     * @param class-string       $requestedName
     *
     * @return bool
     */
    public function canCreate(
        ContainerInterface $container,
        $requestedName
    ): bool {
        return class_exists($requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param class-string       $requestedName
     * @param array|null         $options
     *
     * @return mixed
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        return (new AutoWiringFactory())($container, $requestedName, $options);
    }

}
