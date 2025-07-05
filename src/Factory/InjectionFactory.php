<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Factory;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class InjectionFactory extends AbstractFactory
{
    /**
     * @param class-string                          $requestedName
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $injectionService = $this->getInjectionService($container);

        $injections = $injectionService->resolveConstructorInjection($container, $requestedName);

        if ($injections === false) {
            return new $requestedName();
        }

        return $this->buildInstance($requestedName, $injections);
    }

    private function getInjectionService(ContainerInterface $container): InjectionService
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return $container->get(InjectionService::class);
    }
}
