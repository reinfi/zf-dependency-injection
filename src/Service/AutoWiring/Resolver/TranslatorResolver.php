<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class TranslatorResolver implements ResolverInterface
{
    /**
     * used to avoid requirement of laminas/laminas-i18n module, deprecated interface name.
     */
    private const string TRANSLATOR_INTERFACE_OLD = 'Laminas\I18n\Translator\TranslatorInterface';

    /**
     * used to avoid requirement of laminas/laminas-translator module.
     */
    private const string TRANSLATOR_INTERFACE = 'Laminas\Translator\TranslatorInterface';

    /**
     * possible names for translator service within container
     */
    private const array TRANSLATOR_CONTAINER_SERVICE_NAME = [
        self::TRANSLATOR_INTERFACE,
        'MvcTranslator',
        self::TRANSLATOR_INTERFACE_OLD,
        'Translator',
    ];

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function resolve(ReflectionParameter $reflectionParameter): ?InjectionInterface
    {
        if (! $this->isValid($reflectionParameter)) {
            return null;
        }

        foreach (self::TRANSLATOR_CONTAINER_SERVICE_NAME as $serviceName) {
            if ($this->container->has($serviceName)) {
                return new AutoWiring($serviceName);
            }
        }

        return null;
    }

    private function isValid(ReflectionParameter $reflectionParameter): bool
    {
        $type = $reflectionParameter->getType();
        if (! $type instanceof ReflectionNamedType) {
            return false;
        }

        if (
            ! class_exists($type->getName())
            && ! interface_exists($type->getName())
        ) {
            return false;
        }

        $reflectionClass = new ReflectionClass($type->getName());
        $interfaceNames = $reflectionClass->getInterfaceNames();
        if ($reflectionClass->getName() === self::TRANSLATOR_INTERFACE_OLD) {
            return true;
        }

        if (in_array(self::TRANSLATOR_INTERFACE_OLD, $interfaceNames, true)) {
            return true;
        }

        if ($reflectionClass->getName() === self::TRANSLATOR_INTERFACE) {
            return true;
        }

        return in_array(self::TRANSLATOR_INTERFACE, $interfaceNames, true);
    }
}
