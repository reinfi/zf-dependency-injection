<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectViewHelper;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectViewHelperTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getAnnotationValues
     */
    public function testItCallsPluginManagerWithValue(
        array $values,
        string $className
    ): void {
        $inject = new InjectViewHelper($values);

        $pluginManager = $this->prophesize(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $pluginManager->get($className, $values['options'])
                ->willReturn(true);
        } else {
            $pluginManager->get($className)
                ->willReturn(true);
        }

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('ViewHelperManager')
            ->willReturn($pluginManager->reveal());

        self::assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @dataProvider getAnnotationValues
     */
    public function testItCallsPluginManagerFromParentServiceLocator(
        array $values,
        string $className
    ): void {
        $inject = new InjectViewHelper($values);

        $filterManager = $this->prophesize(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $filterManager->get($className, $values['options'])
                ->willReturn(true);
        } else {
            $filterManager->get($className)
                ->willReturn(true);
        }

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('ViewHelperManager')
            ->willReturn($filterManager->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        self::assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }

    public function getAnnotationValues(): array
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
