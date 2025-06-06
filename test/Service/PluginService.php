<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\InjectParent;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class PluginService
{
    public function __construct(
        /**
         * @InjectParent("Reinfi\DependencyInjection\Test\Service\Service2")
         */
        protected Service2 $service2
    ) {
    }
}
