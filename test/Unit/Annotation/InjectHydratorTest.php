<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Iterator;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectHydrator;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
final class InjectHydratorTest extends TestCase
{
    #[DataProvider('getAnnotationValues')]
    public function testItCallsPluginManagerWithValue(array $values, string $className): void
    {
        $injectHydrator = new InjectHydrator($values);

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
            ->with('HydratorManager')
            ->willReturn($pluginManager);

        self::assertTrue($injectHydrator($container), 'Invoke should return true');
    }

    #[DataProvider('getAnnotationValues')]
    public function testItCallsPluginManagerFromParentServiceLocator(array $values, string $className): void
    {
        $injectHydrator = new InjectHydrator($values);

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
            ->with('HydratorManager')
            ->willReturn($filterManager);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($injectHydrator($pluginManager), 'Invoke should return true');
    }

    #[DataProvider('getAnnotationValues')]
    public function testItShouldReturnContainerServiceKey(array $values, string $expectedValue): void
    {
        $injectHydrator = new InjectHydrator($values);

        // Create a mock container to verify the service name through the __invoke method
        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('get')
            ->with($expectedValue)
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('HydratorManager')
            ->willReturn($pluginManager);

        $injectHydrator($container);
    }

    #[DataProvider('getAnnotationValues')]
    public function testItShouldBuildWithValues(array $values, string $className): void
    {
        $injectHydrator = new InjectHydrator($values);

        // Create a mock container to verify the values through the __invoke method
        $pluginManager = $this->createMock(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($values['value'] ?? $values['name'], $values['options'])
                ->willReturn(true);
        } else {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($values['value'] ?? $values['name'])
                ->willReturn(true);
        }

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('HydratorManager')
            ->willReturn($pluginManager);

        $injectHydrator($container);
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
