<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory\Config;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
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
            self::MATCH_PATTERN,
            $requestedName,
            $this->matches
        ) === 1;
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
}
