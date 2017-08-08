<?php

namespace Reinfi\DependencyInjection\AbstractFactory\Config;

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
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ) {
        return preg_match(static::MATCH_PATTERN, $requestedName, $this->matches) === 1;
    }

    /**
     * @inheritDoc
     */
    public function createServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ) {
        /** @var ConfigService $configService */
        $configService = $serviceLocator->get(ConfigService::class);

        return $configService->resolve($this->matches[1]);
    }
}