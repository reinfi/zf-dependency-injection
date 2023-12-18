<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectViewHelper;
use Reinfi\DependencyInjection\Test\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectViewHelperTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getAttributeValues
     */
    public function testItCallsPluginManagerWithValue(array $values, string $className): void
    {
        $inject = new InjectViewHelper(...array_values($values));

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

        self::assertTrue($inject($container->reveal()), 'Invoke should return true');
    }

    /**
     * @dataProvider getAttributeValues
     */
    public function testItCallsPluginManagerFromParentServiceLocator(array $values, string $className): void
    {
        $inject = new InjectViewHelper(...array_values($values));

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

        self::assertTrue($inject($pluginManager->reveal()), 'Invoke should return true');
    }

    public static function getAttributeValues(): array
    {
        return [
            [
                [
                    'name' => Service1::class,
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
        ];
    }
}
