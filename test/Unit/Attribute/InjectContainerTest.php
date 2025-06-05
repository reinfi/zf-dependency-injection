<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectContainerTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $injectContainer = new InjectContainer();

        $container = $this->createMock(ContainerInterface::class);

        self::assertEquals($container, $injectContainer($container), 'Invoke should return provided container');
    }
}
