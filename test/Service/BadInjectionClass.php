<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Service;

/**
 * Does not implements InjectionInterface.
 *
 * @package Reinfi\DependencyInjection\Test\Service
 */
class BadInjectionClass
{
    public function __construct(
        private readonly array $property
    ) {
    }
}
