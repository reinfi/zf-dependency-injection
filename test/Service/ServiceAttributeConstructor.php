<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Attribute\Inject;

class ServiceAttributeConstructor
{
    private Service2 $service2;

    #[Inject('Reinfi\DependencyInjection\Test\Service\Service2')]
    public function __construct(Service2 $service2)
    {
        $this->service2 = $service2;
    }
}
