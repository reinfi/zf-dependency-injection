<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Attribute\Inject;
use Reinfi\DependencyInjection\Attribute\InjectConfig;
use Reinfi\DependencyInjection\Attribute\InjectConstant;

class ServiceAttribute
{
    #[Inject('Reinfi\DependencyInjection\Test\Service\Service2')]
    private Service2 $service2;

    #[InjectConstant(PHP_VERSION)]
    private string $version;

    #[InjectConfig('test.value')]
    private int $testValue;

    public function __construct(
        Service2 $service2,
        string   $version,
        int      $testValue
    ) {
        $this->service2 = $service2;
        $this->version = $version;
        $this->testValue = $testValue;
    }
}
