<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory\Config;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\AbstractFactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\AbstractFactory\Config
 */
class InjectConfigAbstractFactory implements AbstractFactoryInterface
{
    private const MATCH_PATTERN = '/^Config\.(.*)$/';

    /**
     * @var array
     */
    private array $matches = [];

    /**
     * @inheritDoc
     */
    public function canCreate(
        ContainerInterface $container,
        $requestedName
    ): bool {
        return preg_match(
            static::MATCH_PATTERN,
            $requestedName,
            $this->matches
        ) === 1;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param string                  $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ): bool {
        return $this->canCreate($serviceLocator, $requestedName);
    }

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return mixed|object|null
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var ConfigService $configService */
        $configService = $container->get(ConfigService::class);

        return $configService->resolve($this->matches[1]);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param string                  $requestedName
     *
     * @return mixed|object|null
     */
    public function createServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ) {
        return $this($serviceLocator, $requestedName);
    }
}
