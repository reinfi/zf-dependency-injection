<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringPluginManager;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class PluginManagerResolver implements ResolverInterface
{
    /**
     * @var array
     */
    protected static $pluginManagerMapping = [
        'Zend\Hydrator\HydratorInterface'       => 'HydratorManager',
        'Zend\View\Helper\HelperInterface'      => 'ViewHelperManager',
        'Zend\Validator\ValidatorInterface'     => 'ValidatorManager',
        'Zend\Filter\FilterInterface'           => 'FilterManager',
        'Zend\InputFilter\InputFilterInterface' => 'InputFilterManager',
        'Zend\InputFilter\InputInterface'       => 'InputFilterManager',
        'Zend\Form\ElementInterface'            => 'FormElementManager',
    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function resolve(ReflectionParameter $parameter): ?InjectionInterface
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        $reflectionClass = $parameter->getClass();
        $serviceName = $reflectionClass->getName();

        $interfaceNames = $reflectionClass->getInterfaceNames();

        foreach (self::$pluginManagerMapping as $interfaceName => $pluginManager) {
            if (
                in_array($interfaceName, $interfaceNames)
                && $this->container->get($pluginManager)->has($serviceName)
            ) {
                return new AutoWiringPluginManager($pluginManager, $serviceName);
            }
        }

        return null;
    }

    /**
     * @param string $className
     * @param string $pluginManager
     */
    public static function addMapping(string $className, string $pluginManager)
    {
        static::$pluginManagerMapping[$className] = $pluginManager;
    }
}
