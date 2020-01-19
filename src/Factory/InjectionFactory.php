<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class InjectionFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    public function __invoke(
        \Interop\Container\ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $injectionService = $this->getInjectionService($container);

        $injections = $injectionService->resolveConstructorInjection(
            $container,
            $requestedName
        );

        if ($injections === false) {
            return new $requestedName;
        }

        return $this->buildInstance($requestedName, $injections);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return InjectionService
     */
    private function getInjectionService(ContainerInterface $container): InjectionService
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return $container->get(InjectionService::class);
    }
}
