<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * Interface ExtractorInterface
 *
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
interface ExtractorInterface
{
    /**
     * @param string $className
     *
     * @return InjectionInterface[]
     */
    public function getPropertiesInjections(string $className): array;

    /**
     * @param string $className
     *
     * @return array
     */
    public function getConstructorInjections(string $className): array;
}
