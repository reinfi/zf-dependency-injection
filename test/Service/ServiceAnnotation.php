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
    /**
     * @Inject("Reinfi\DependencyInjection\Test\Service\Service2")
     *
     * @var Service2
     */
    protected $service2;

    /**
     * @InjectConfig("test.value")
     *
     * @var int
     */
    protected $value;

    /**
     * @InjectConfig("test", asArray=true)
     *
     * @var array
     */
    protected $valueAsArray;

    public function __construct(Service2 $service2, int $value, array $valueAsArray)
    {
        $this->service2 = $service2;
        $this->value = $value;
        $this->valueAsArray = $valueAsArray;
    }
}
