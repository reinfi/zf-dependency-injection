<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory
 */
class TranslatorResolverFactory
{
    public function __invoke(ContainerInterface $container): TranslatorResolver
    {
        return new TranslatorResolver($container);
    }
}
