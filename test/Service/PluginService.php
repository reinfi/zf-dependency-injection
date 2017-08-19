<?php

namespace Reinfi\DependencyInjection\Service;

use Reinfi\DependencyInjection\Annotation\InjectParent;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class PluginService
{
    /**
     * @InjectParent("Reinfi\DependencyInjection\Service\Service2")
     *
     * @var Service2
     */
    protected $service2;

    /**
     * @param Service2 $service2
     */
    public function __construct(Service2 $service2)
    {
        $this->service2 = $service2;
    }
}