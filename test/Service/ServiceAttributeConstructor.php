<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Attribute\Inject;

class ServiceAttributeConstructor
{
    #[Inject('Reinfi\DependencyInjection\Test\Service\Service2')]
    public function __construct(
        private readonly Service2 $service2
    ) {
    }
}
