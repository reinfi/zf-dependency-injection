<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Attribute\Inject;
use Reinfi\DependencyInjection\Attribute\InjectConfig;
use Reinfi\DependencyInjection\Attribute\InjectConstant;

class ServiceAttribute81
{
    public function __construct(
        #[Inject('Reinfi\DependencyInjection\Test\Service\Service2')]
        private Service2 $service2,
        #[InjectConfig('test.value')]
        private int $testValue
    ) {
    }
}
