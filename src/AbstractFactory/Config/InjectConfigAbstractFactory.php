<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\AbstractFactory\Config;

use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

/**
 * @package Reinfi\DependencyInjection\AbstractFactory\Config
 */
class InjectConfigAbstractFactory implements AbstractFactoryInterface
{
    private const string MATCH_PATTERN = '/^Config\.(.*)$/';

    private array $matches = [];

    /**
     * @param string             $requestedName
     *
     * @return mixed|object|null
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        /** @var ConfigService $configService */
        $configService = $container->get(ConfigService::class);

        return $configService->resolve($this->matches[1]);
    }

    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        return preg_match(self::MATCH_PATTERN, $requestedName, $this->matches) === 1;
    }
}
