<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceNoTypeHint
{
    public function __construct(Service2 $service2)
    {
    }
}
