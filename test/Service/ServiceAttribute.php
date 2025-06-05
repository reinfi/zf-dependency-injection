<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Attribute\Inject;
use Reinfi\DependencyInjection\Attribute\InjectConfig;
use Reinfi\DependencyInjection\Attribute\InjectConstant;

class ServiceAttribute
{
    public function __construct(
        #[Inject('Reinfi\DependencyInjection\Test\Service\Service2')]
        private readonly Service2 $service2,
        #[InjectConstant(PHP_VERSION)]
        private readonly string $version,
        #[InjectConfig('test.value')]
        private readonly int $testValue
    ) {
    }
}
