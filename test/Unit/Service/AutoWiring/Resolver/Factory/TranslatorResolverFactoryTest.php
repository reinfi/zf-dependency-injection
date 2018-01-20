<?php

namespace Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\TranslatorResolverFactory;

/**
 * @package Reinfi\DependencyInjection\Unit\Service\AutoWiring\Resolver\Factory
 */
class TranslatorResolverFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTranslatorResolver()
    {
        $container = $this->prophesize(ContainerInterface::class);

        $factory = new TranslatorResolverFactory();

        $this->assertInstanceOf(
            TranslatorResolver::class,
            $factory($container->reveal())
        );
    }
}