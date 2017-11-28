<?php

namespace Reinfi\DependencyInjection\Service;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class ServiceNoTypeHint
{
    /**
     * @param Service2 $service2
     */
    public function __construct(
        $service2
    ) {
    }
}