<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectConstant;
use Reinfi\DependencyInjection\Test\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectConstantTest extends TestCase
{
    public function testItShouldConvertScalarTypes(): void
    {
        $injectScalar = new InjectConstant();
        $injectScalar->value = Service2::class . '::CONSTANT';

        $container = $this->createMock(ContainerInterface::class);

        self::assertSame(Service2::CONSTANT, $injectScalar($container));
    }
}
