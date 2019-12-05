<?php

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceBuildInTypeWithDefaultUsingConstant
{
    private const DEFAULT = 1;

    /**
     * @param Service1 $service1
     * @param int      $service2
     * @param int      $service3
     */
    public function __construct(
        Service1 $service1,
        int $service2 = self::DEFAULT,
        int $service3 = ServiceBuildInTypeWithDefault::FOREIGN_DEFAULT
    ) {
    }
}
