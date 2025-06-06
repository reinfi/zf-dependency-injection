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
     * @Inject("Reinfi\DependencyInjection\Test\Service\Service2")
     * @InjectConfig("test.value")
     * @InjectConstant("Reinfi\DependencyInjection\Test\Service\Service2::CONSTANT")
     */
    public function __construct(
        protected Service2 $service2,
        protected int $value,
        protected string $constant
    ) {
    }
}
