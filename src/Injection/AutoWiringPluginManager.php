<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiringPluginManager implements InjectionInterface
{
    private string $pluginManager;

    private string $serviceName;

    public function __construct(
        string $pluginManager,
        string $serviceName
    ) {
        $this->pluginManager = $pluginManager;
        $this->serviceName = $serviceName;
    }

    /**
     * @return mixed
     * @throws AutoWiringNotPossibleException
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        $pluginManagerImplemenation = $container->get($this->pluginManager);
        if (
            $pluginManagerImplemenation instanceof ContainerInterface
            && $pluginManagerImplemenation->has($this->serviceName)
        ) {
            return $pluginManagerImplemenation->get($this->serviceName);
        }

        throw new AutoWiringNotPossibleException($this->serviceName);
    }
}
