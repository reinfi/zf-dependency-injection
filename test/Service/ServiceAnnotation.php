<?php

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceAnnotation
{
    /**
     * @Inject("Reinfi\DependencyInjection\Test\Service\Service2")
     *
     * @var Service2
     */
    protected $service2;

    /**
     * @InjectConfig("test.value")
     *
     * @var int
     */
    protected $value;

    /**
     * @param Service2 $service2
     * @param int      $value
     */
    public function __construct(Service2 $service2, int $value)
    {
        $this->service2 = $service2;
        $this->value = $value;
    }
}
