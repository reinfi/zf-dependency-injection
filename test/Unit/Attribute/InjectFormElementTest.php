<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Iterator;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectFormElement;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectFormElementTest extends TestCase
{
    #[DataProvider('getAttributeValues')]
    public function testItCallsPluginManagerWithValue(array $values, string $className): void
    {
        $injectFormElement = new InjectFormElement(...array_values($values));

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
            ->with('FormElementManager')
            ->willReturn($pluginManager);

        self::assertTrue($injectFormElement($container), 'Invoke should return true');
    }

    #[DataProvider('getAttributeValues')]
    public function testItCallsPluginManagerFromParentServiceLocator(array $values, string $className): void
    {
        $injectFormElement = new InjectFormElement(...array_values($values));

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
            ->with('FormElementManager')
            ->willReturn($filterManager);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertTrue($injectFormElement($pluginManager), 'Invoke should return true');
    }

    public static function getAttributeValues(): Iterator
    {
        yield [
            [
                'name' => Service1::class,
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
    }
}
