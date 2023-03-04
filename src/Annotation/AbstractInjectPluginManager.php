<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\InjectionNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Annotation
 */
abstract class AbstractInjectPluginManager extends AbstractAnnotation
{
    public const PLUGIN_MANAGER = '';

    private string $name;

    private ?array $options = null;

    public function __construct(array $values)
    {
        if (! isset($values['value'])) {
            if (isset($values['options'])) {
                $this->options = $values['options'];
            }

            $this->name = $values['name'];

            return;
        }

        $this->name = $values['value'];
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
