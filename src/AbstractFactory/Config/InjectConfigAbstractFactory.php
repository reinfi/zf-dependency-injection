<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory\Config;

use Interop\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\AbstractFactory\Config
 */
class InjectConfigAbstractFactory implements AbstractFactoryInterface
{
    const MATCH_PATTERN = '/^Config\.(.*)$/';

    /**
     * @var array
     */
    private $matches;

    /**
     * @inheritDoc
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        return preg_match(static::MATCH_PATTERN, $requestedName, $this->matches) === 1;
    }

    /**
     * @inheritDoc
     */
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ): bool {
        return $this->canCreate($serviceLocator, $requestedName);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function createServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ) {
        return $this($serviceLocator, $requestedName);
    }
}
