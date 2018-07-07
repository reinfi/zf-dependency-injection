<?php

namespace Reinfi\DependencyInjection\Service;

/**
 * Does not implements InjectionInterface.
 *
 * @package Reinfi\DependencyInjection\Service
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
