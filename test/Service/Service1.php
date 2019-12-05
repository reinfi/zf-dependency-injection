<?php

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class Service1
{
    /**
     * @param Service2 $service2
     * @param Service3 $service3
     * @param string   $foo
     */
    public function __construct(
        Service2 $service2,
        Service3 $service3,
        string $foo = ''
    ) {
    }
}
