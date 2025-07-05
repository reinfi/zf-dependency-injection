<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceBuildInTypeWithDefault
{
    public const int FOREIGN_DEFAULT = 5;

    public function __construct(Service1 $service1, int $service2 = 0)
    {
    }
}
