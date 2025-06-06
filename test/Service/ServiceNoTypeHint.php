<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceNoTypeHint
{
    /**
     * @param Service2 $service2
     */
    public function __construct($service2)
    {
    }
}
