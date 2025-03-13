<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class Value implements InjectionInterface
{
    public function __construct(
        private readonly mixed $value
    ) {
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        return $this->value;
    }
}
