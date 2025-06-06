<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectHydrator;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectHydratorTest extends TestCase
{
    #[DataProvider('getAnnotationValues')]
    public function testItCallsPluginManagerWithValue(array $values, string $className): void
    {
        $inject = new InjectHydrator($values);

        $pluginManager = $this->createMock(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($this->equalTo($className), $this->equalTo($values['options']))
                ->willReturn(true);
        } else {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($this->equalTo($className))
                ->willReturn(true);
        }

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('HydratorManager'))
            ->willReturn($pluginManager);

        self::assertTrue($inject($container), 'Invoke should return true');
    }

    #[DataProvider('getAnnotationValues')]
    public function testItCallsPluginManagerFromParentServiceLocator(array $values, string $className): void
    {
        $inject = new InjectHydrator($values);

        $filterManager = $this->createMock(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $filterManager->expects($this->once())
                ->method('get')
                ->with($this->equalTo($className), $this->equalTo($values['options']))
                ->willReturn(true);
        } else {
            $filterManager->expects($this->once())
                ->method('get')
                ->with($this->equalTo($className))
                ->willReturn(true);
        }

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('HydratorManager'))
            ->willReturn($filterManager);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($inject($pluginManager), 'Invoke should return true');
    }

    #[DataProvider('getAnnotationValues')]
    public function testItShouldReturnContainerServiceKey(array $values, string $expectedValue): void
    {
        $inject = new InjectHydrator($values);

        // Create a mock container to verify the service name through the __invoke method
        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo($expectedValue))
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('HydratorManager'))
            ->willReturn($pluginManager);

        $inject($container);
    }

    #[DataProvider('getAnnotationValues')]
    public function testItShouldBuildWithValues(array $values, string $className): void
    {
        $inject = new InjectHydrator($values);

        // Create a mock container to verify the values through the __invoke method
        $pluginManager = $this->createMock(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($this->equalTo($values['value'] ?? $values['name']), $this->equalTo($values['options']))
                ->willReturn(true);
        } else {
            $pluginManager->expects($this->once())
                ->method('get')
                ->with($this->equalTo($values['value'] ?? $values['name']))
                ->willReturn(true);
        }

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('HydratorManager'))
            ->willReturn($pluginManager);

        $inject($container);
    }

    public static function getAnnotationValues(): array
    {
        return [
            [
                [
                    'value' => Service1::class,
                ],
                Service1::class,
            ],
            [
                [
                    'name' => Service1::class,
                    'options' => [
                        'field' => true,
                    ],
                ],
                Service1::class,
            ],
            [
                [
                    'name' => Service1::class,
                ],
                Service1::class,
            ],
        ];
    }
}
