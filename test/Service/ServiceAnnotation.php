<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceAnnotation
{
    public function __construct(
        /**
         * @Inject("Reinfi\DependencyInjection\Test\Service\Service2")
         */
        protected Service2 $service2,
        /**
         * @InjectConfig("test.value")
         */
        protected int $value,
        /**
         * @InjectConfig("test", asArray=true)
         */
        protected array $valueAsArray
    ) {
    }
}
