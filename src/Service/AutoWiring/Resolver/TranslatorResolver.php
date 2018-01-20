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
     * possible names for translator service within container
     */
    const TRANSLATOR_CONTAINER_SERVICE_NAME = [
        'MvcTranslator',
        self::TRANSLATOR_INTERFACE,
        'Translator'
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
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter)
    {
        if (!$this->isValid($parameter)) {
            return null;
        }

        foreach (self::TRANSLATOR_CONTAINER_SERVICE_NAME as $serviceName) {
            if ($this->container->has($serviceName)) {
                return new AutoWiring($serviceName);
            }
        }

        return null;
    }

    /**
     * @param ReflectionParameter $parameter
     *
     * @return bool
     */
    private function isValid(ReflectionParameter $parameter): bool
    {
        if ($parameter->getClass() === null) {
            return false;
        }

        $reflectionClass = $parameter->getClass();
        $interfaceNames = $reflectionClass->getInterfaceNames();

        return (
            $reflectionClass->getName() === self::TRANSLATOR_INTERFACE
            || in_array(self::TRANSLATOR_INTERFACE, $interfaceNames)
        );
    }
}
