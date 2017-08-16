<?php

namespace Reinfi\DependencyInjection\Service;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class Service1
{
    /**
     * @param Service2 $service2
     */
    public function __construct(
        Service2 $service2,
        Service3 $service3
    ) {
    }
}