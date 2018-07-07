<?php

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Annotation
 */
abstract class AbstractInjectPluginManager extends AbstractAnnotation
{
    /**
     * @var string
     */
    const PLUGIN_MANAGER = '';

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (!isset($values['value'])) {
            if (isset($values['options'])) {
                $this->options = $values['options'];
            }

            $this->name = $values['name'];

            return;
        }

        $this->name = $values['value'];
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        $container = $this->determineContainer($container);

        if (is_array($this->options)) {
            return $container->get(static::PLUGIN_MANAGER)
                ->get($this->name, $this->options);
        }

        return $container->get(static::PLUGIN_MANAGER)
            ->get($this->name);
    }
}
