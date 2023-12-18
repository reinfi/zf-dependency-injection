<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\Inject;
use Reinfi\DependencyInjection\Service\InjectionService;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectTest extends TestCase
{
    use ProphecyTrait;

    public function testItCallsContainerWithValue(): void
    {
        $inject = new Inject(InjectionService::class);

        $container = $this->prophesize(ContainerInterface::class);

        $container->get(InjectionService::class)
            ->willReturn(true);

        self::assertTrue($inject($container->reveal()), 'Invoke should return true');
    }
}
