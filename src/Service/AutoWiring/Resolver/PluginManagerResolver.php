<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class PluginManagerResolver implements ResolverInterface
{
    /**
     * @var array<class-string, string>
     */
    protected static array $pluginManagerMapping = [
        'Laminas\Hydrator\HydratorInterface' => 'HydratorManager',
        'Laminas\View\Helper\HelperInterface' => 'ViewHelperManager',
        'Laminas\Validator\ValidatorInterface' => 'ValidatorManager',
        'Laminas\Filter\FilterInterface' => 'FilterManager',
        'Laminas\InputFilter\InputFilterInterface' => 'InputFilterManager',
        'Laminas\InputFilter\InputInterface' => 'InputFilterManager',
        'Laminas\Form\ElementInterface' => 'FormElementManager',
    ];

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        $type = $parameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        if (
            ! class_exists($type->getName())
            && ! interface_exists($type->getName())
        ) {
            return null;
        }

        $reflectionClass = new ReflectionClass($type->getName());

        return $this->handleClass($reflectionClass);
    }

    /**
     * @param class-string $className
     */
    public static function addMapping(string $className, string $pluginManager): void
    {
        static::$pluginManagerMapping[$className] = $pluginManager;
    }

    private function handleClass(ReflectionClass $reflectionClass): ?AutoWiringPluginManager
    {
        $serviceName = $reflectionClass->getName();

        $interfaceNames = $reflectionClass->getInterfaceNames();

        foreach (self::$pluginManagerMapping as $interfaceName => $pluginManager) {
            if (in_array($interfaceName, $interfaceNames, true)) {
                $pluginManagerImplementation = $this->container->get($pluginManager);
                if (
                    $pluginManagerImplementation instanceof ContainerInterface
                    && $pluginManagerImplementation->has($serviceName)
                ) {
                    return new AutoWiringPluginManager($pluginManager, $serviceName);
                }
            }
        }

        return null;
    }
}
