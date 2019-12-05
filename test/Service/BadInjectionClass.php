<?php

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * Does not implements InjectionInterface.
 *
 * @package Reinfi\DependencyInjection\Test\Service
 */
class BadInjectionClass
{
    /**
     * @var array
     */
    private $property;

    /**
     * @param array $property
     */
    public function __construct(array $property)
    {
        $this->property = $property;
    }
}
