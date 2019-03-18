<?php

namespace Reinfi\DependencyInjection\Service;

use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class ServiceAnnotationConstructor
{
    /**
     * @var Service2
     */
    protected $service2;

    /**
     * @var int
     */
    protected $value;

    /**
     * @Inject("Reinfi\DependencyInjection\Service\Service2")
     * @InjectConfig("test.value")
     *
     * @param Service2 $service2
     * @param int      $value
     */
    public function __construct(Service2 $service2, int $value)
    {
        $this->service2 = $service2;
        $this->value = $value;
    }
}
