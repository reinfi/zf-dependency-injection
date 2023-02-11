<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Annotation\InjectConstant;

/**
 * @package Reinfi\DependencyInjection\Test\Service
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
     * @Inject("Reinfi\DependencyInjection\Test\Service\Service2")
     * @InjectConfig("test.value")
     * @InjectConstant("Reinfi\DependencyInjection\Test\Service\Service2::CONSTANT")
     */
    public function __construct(Service2 $service2, int $value, string $constant)
    {
        $this->service2 = $service2;
        $this->value = $value;
        $this->constant = $constant;
    }
}
