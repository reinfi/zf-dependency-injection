<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectContainerTest extends TestCase
{
    use ProphecyTrait;

    public function testItCallsContainerWithValue(): void
    {
        $inject = new InjectContainer();

        $container = $this->prophesize(ContainerInterface::class);

        self::assertEquals(
            $container->reveal(),
            $inject($container->reveal()),
            'Invoke should return provided container'
        );
    }
}
