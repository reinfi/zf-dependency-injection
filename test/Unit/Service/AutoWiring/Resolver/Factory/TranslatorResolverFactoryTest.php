<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\Factory\TranslatorResolverFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\TranslatorResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver\Factory
 */
final class TranslatorResolverFactoryTest extends TestCase
{
    public function testItReturnsTranslatorResolver(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $translatorResolverFactory = new TranslatorResolverFactory();

        self::assertInstanceOf(TranslatorResolver::class, $translatorResolverFactory($container));
    }
}
