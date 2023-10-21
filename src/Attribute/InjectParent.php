<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;
use Laminas\ServiceManager\AbstractPluginManager;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class InjectParent implements InjectionInterface
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }

        return $container->get($this->value);
    }
}
