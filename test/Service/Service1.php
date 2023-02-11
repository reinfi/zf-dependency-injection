<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class Service1
{
    public function __construct(
        Service2 $service2,
        Service3 $service3,
        string $foo = ''
    ) {
    }
}
