<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceBuildInTypeWithDefaultUsingConstant
{
    private const int DEFAULT = 1;

    public function __construct(
        Service1 $service1,
        int $service2 = self::DEFAULT,
        int $service3 = ServiceBuildInTypeWithDefault::FOREIGN_DEFAULT
    ) {
    }
}
