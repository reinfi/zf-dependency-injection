<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectConstant;
use Reinfi\DependencyInjection\Service\InjectionService;
use Reinfi\DependencyInjection\Test\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectConstantTest extends TestCase
{
    public function testItShouldConvertScalarTypes(): void
    {
        $injectConstant = new InjectConstant(Service2::class . '::CONSTANT');

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(InjectionService::class)
            ->willReturn(true);

        self::assertSame(Service2::CONSTANT, $injectConstant($container));
    }
}
