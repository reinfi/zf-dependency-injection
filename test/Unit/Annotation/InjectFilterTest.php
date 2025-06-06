<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Iterator;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectFilter;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
final class InjectFilterTest extends TestCase
{
    #[DataProvider('getAnnotationValues')]
    public function testItCallsPluginManagerWithValue(array $values, string $className): void
    {
        $injectFilter = new InjectFilter($values);

        $pluginManager = $this->createMock(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($className, $values['options'])
                ->willReturn(true);
        } else {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($className)
                ->willReturn(true);
        }

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('FilterManager')
            ->willReturn($pluginManager);

        self::assertTrue($injectFilter($container), 'Invoke should return true');
    }

    #[DataProvider('getAnnotationValues')]
    public function testItCallsPluginManagerFromParentServiceLocator(array $values, string $className): void
    {
        $injectFilter = new InjectFilter($values);

        $filterManager = $this->createMock(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $filterManager->expects($this->once())
                ->method('get')
                ->with($className, $values['options'])
                ->willReturn(true);
        } else {
            $filterManager->expects($this->once())
                ->method('get')
                ->with($className)
                ->willReturn(true);
        }

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('FilterManager')
            ->willReturn($filterManager);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($injectFilter($pluginManager), 'Invoke should return true');
    }

    public static function getAnnotationValues(): Iterator
    {
        yield [
            [
                'value' => Service1::class,
            ],
            Service1::class,
        ];
        yield [
            [
                'name' => Service1::class,
                'options' => [
                    'field' => true,
                ],
            ],
            Service1::class,
        ];
        yield [
            [
                'name' => Service1::class,
            ],
            Service1::class,
        ];
    }
}
