<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiringService;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class AutoWiringFactory extends AbstractFactory
{
    /**
     * @param class-string                          $requestedName
     *
     * @return mixed
     */
    public function __invoke(
        \Interop\Container\ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $autoWiringService = $this->getAutoWiringService($container);

        $injections = $autoWiringService->resolveConstructorInjection($container, $requestedName, $options);

        if ($injections === null) {
            return new $requestedName();
        }

        return $this->buildInstance($requestedName, $injections);
    }

    private function getAutoWiringService(ContainerInterface $container): AutoWiringService
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return $container->get(AutoWiringService::class);
    }
}
