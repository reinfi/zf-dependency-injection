<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
interface ExtractorInterface
{
    /**
     * @param class-string $className
     *
     * @return InjectionInterface[]
     */
    public function getPropertiesInjections(string $className): array;

    /**
     * @param class-string $className
     */
    public function getConstructorInjections(string $className): array;
}
