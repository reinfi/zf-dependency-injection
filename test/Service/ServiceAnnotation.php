<?php

namespace Reinfi\DependencyInjection\Service;

use Reinfi\DependencyInjection\Annotation\Inject;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class ServiceAnnotation
{
    /**
     * @Inject("Reinfi\DependencyInjection\Service\Service2")
     *
     * @var Service2
     */
    protected $service2;

    /**
     * @Inject("Reinfi\DependencyInjection\Service\Service2")
     *
     * @param Service2 $service2
     */
    public function __construct(Service2 $service2)
    {
        $this->service2 = $service2;
    }
}