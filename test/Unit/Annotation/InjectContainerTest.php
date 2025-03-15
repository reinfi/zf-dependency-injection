<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectContainer;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectContainerTest extends TestCase
{
    public function testItCallsContainerWithValue(): void
    {
        $inject = new InjectContainer();

        $container = $this->createMock(ContainerInterface::class);

        self::assertEquals($container, $inject($container), 'Invoke should return provided container');
    }
}
