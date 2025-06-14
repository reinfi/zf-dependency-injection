<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\InjectionNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
abstract class AbstractInjectPluginManager extends AbstractAttribute
{
    public const string PLUGIN_MANAGER = '';

    public function __construct(
        private readonly string $name,
        private readonly ?array $options = null
    ) {
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        $container = $this->determineContainer($container);
        $pluginManagerImplementation = $container->get(static::PLUGIN_MANAGER);

        if (! $pluginManagerImplementation instanceof AbstractPluginManager) {
            throw InjectionNotPossibleException::fromUnknownPluginManager(static::PLUGIN_MANAGER);
        }

        if (is_array($this->options)) {
            return $pluginManagerImplementation->get($this->name, $this->options);
        }

        return $pluginManagerImplementation->get($this->name);
    }
}
