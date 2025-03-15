<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\ContainerResolverFactory;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory
 */
class ContainerResolverFactoryTest extends TestCase
{
    public function testItReturnsContainerResolver(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $factory = new ContainerResolverFactory();

        self::assertInstanceOf(ContainerResolver::class, $factory($container));
    }
}
