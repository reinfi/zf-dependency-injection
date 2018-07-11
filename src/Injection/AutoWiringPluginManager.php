<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiringPluginManager implements InjectionInterface
{
    /**
     * @var string
     */
    private $pluginManager;

    /**
     * @var string
     */
    private $serviceName;

    /**
     * @param string $pluginManager
     * @param string $serviceName
     */
    public function __construct(
        string $pluginManager,
        string $serviceName
    ) {
        $this->pluginManager = $pluginManager;
        $this->serviceName = $serviceName;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return mixed
     * @throws AutoWiringNotPossibleException
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        if ($container->get($this->pluginManager)->has($this->serviceName)) {
            return $container->get($this->pluginManager)->get($this->serviceName);
        }

        throw new AutoWiringNotPossibleException($this->serviceName);
    }
}
