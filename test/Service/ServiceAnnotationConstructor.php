<?php

namespace Reinfi\DependencyInjection\Service;

use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Annotation\InjectConstant;

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
     * @var string
     */
    protected $constant;

    /**
     * @Inject("Reinfi\DependencyInjection\Service\Service2")
     * @InjectConfig("test.value")
     * @InjectConstant("Reinfi\DependencyInjection\Service\Service2::CONSTANT")
     *
     * @param Service2 $service2
     * @param int      $value
     * @param string     $constant
     */
    public function __construct(Service2 $service2, int $value, string $constant)
    {
        $this->service2 = $service2;
        $this->value = $value;
        $this->constant = $constant;
    }
}
