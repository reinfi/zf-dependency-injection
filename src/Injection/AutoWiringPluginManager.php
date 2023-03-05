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
     * @throws AutoWiringNotPossibleException
     */
    public function __invoke(ContainerInterface $container): mixed
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        $pluginManagerImplementation = $container->get($this->pluginManager);
        if (
            $pluginManagerImplementation instanceof ContainerInterface
            && $pluginManagerImplementation->has($this->serviceName)
        ) {
            return $pluginManagerImplementation->get($this->serviceName);
        }

        throw new AutoWiringNotPossibleException($this->serviceName);
    }
}
