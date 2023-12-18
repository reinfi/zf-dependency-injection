<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\TranslatorResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory
 */
class TranslatorResolverFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsTranslatorResolver(): void
    {
        $container = $this->prophesize(ContainerInterface::class);

        $factory = new TranslatorResolverFactory();

        self::assertInstanceOf(TranslatorResolver::class, $factory($container->reveal()));
    }
}
