<?php

namespace Reinfi\DependencyInjection\Test\Unit\Controller\Factory;

use Laminas\Mvc\Controller\ControllerManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Controller\CacheWarmupController;
use Reinfi\DependencyInjection\Controller\Factory\CacheWarmupControllerFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Controller\Factory
 */
class CacheWarmupControllerFactoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itCreatesController(): void
    {
        if (!class_exists('Laminas\Mvc\Console\Controller\AbstractConsoleController')) {
            $this->markTestSkipped('Skipped because zend console for zend version 3 is not installed');
        }

        $controllerManager = $this->prophesize(ControllerManager::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')
            ->willReturn(['service_manager' => []]);
        $container->get(ExtractorInterface::class)
            ->willReturn(
                $this->prophesize(ExtractorInterface::class)->reveal()
            );
        $container->get(ResolverService::class)
            ->willReturn(
                $this->prophesize(ResolverService::class)->reveal()
            );
        $container->get(CacheService::class)
            ->willReturn(
                $this->prophesize(CacheService::class)->reveal()
            );
        $controllerManager->getServiceLocator()
            ->willReturn($container->reveal());

        $factory = new CacheWarmupControllerFactory();

        $instance = $factory($controllerManager->reveal());

        self::assertInstanceOf(
            CacheWarmupController::class,
            $instance
        );
    }
}
