<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiring implements InjectionInterface
{
    public function __construct(
        private readonly string $serviceName
    ) {
    }

    /**
     * @throws AutoWiringNotPossibleException
     */
    public function __invoke(ContainerInterface $container): mixed
    {
        if ($container->has($this->serviceName)) {
            return $container->get($this->serviceName);
        }

        if ($container instanceof AbstractPluginManager && $container->getServiceLocator()->has($this->serviceName)) {
            return $container->getServiceLocator()->get($this->serviceName);
        }

        throw new AutoWiringNotPossibleException($this->serviceName);
    }
}
