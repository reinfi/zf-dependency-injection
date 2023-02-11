<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
interface ResolverServiceInterface
{
    /**
     * @return InjectionInterface[]
     */
    public function resolve(string $className, ?array $options = null): array;
}
