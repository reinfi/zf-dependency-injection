<?php

namespace Reinfi\DependencyInjection\Integration\Factory;

use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Integration\AbstractIntegrationTest;
use Reinfi\DependencyInjection\Service\Service1;

/**
 * @package Reinfi\DependencyInjection\Integration\Factory
 */
class AutoWiringFactoryTest extends AbstractIntegrationTest
{
    /**
     * @test
     */
    public function itCreatesServiceWithDependencies()
    {
        $container = $this->getServiceManager(require __DIR__ . '/../../resources/config.php');

        $factory = new AutoWiringFactory();

        $instance = $factory->createService(
            $container,
            Service1::class,
            Service1::class
        );

        $this->assertInstanceOf(
            Service1::class,
            $instance
        );
    }
}