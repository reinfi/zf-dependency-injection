<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\InjectParent;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class PluginService
{
    /**
     * @InjectParent("Reinfi\DependencyInjection\Test\Service\Service2")
     *
     * @var Service2
     */
    protected $service2;

    public function __construct(Service2 $service2)
    {
        $this->service2 = $service2;
    }
}
