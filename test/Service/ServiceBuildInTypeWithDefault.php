<?php

namespace Reinfi\DependencyInjection\Service;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class ServiceBuildInTypeWithDefault
{
    public const FOREIGN_DEFAULT = 5;

    /**
     * @param Service1 $service1
     * @param int      $service2
     */
    public function __construct(
        Service1 $service1,
        int $service2 = 0
    ) {
    }
}
