<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class TranslatorResolver implements ResolverInterface
{
    /**
     * used to avoid requirement of zendframework/zend-i18n module
     */
    const TRANSLATOR_INTERFACE = 'Zend\I18n\Translator\TranslatorInterface';

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
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter)
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        $reflectionClass = $parameter->getClass();
        $interfaceNames = $reflectionClass->getInterfaceNames();

        if (
            $reflectionClass->getName() !== self::TRANSLATOR_INTERFACE
            && !in_array(self::TRANSLATOR_INTERFACE, $interfaceNames)
        ) {
            return null;
        }

        if ($this->container->has('MvcTranslator')) {
            return new AutoWiring('MvcTranslator');
        }

        if ($this->container->has(self::TRANSLATOR_INTERFACE)) {
            return new AutoWiring(self::TRANSLATOR_INTERFACE);
        }

        if ($this->container->has('Translator')) {
            return new AutoWiring('Translator');
        }

        return null;
    }
}