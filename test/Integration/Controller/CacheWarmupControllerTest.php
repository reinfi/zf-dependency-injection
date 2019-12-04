<?php

namespace Reinfi\DependencyInjection\Test\Integration\Controller;

use Prophecy\Argument;
use Reinfi\DependencyInjection\Controller\CacheWarmupController;
use Reinfi\DependencyInjection\Test\Integration\AbstractIntegrationTest;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\Console\Adapter\AdapterInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Controller
 *
 * @group integration
 */
class CacheWarmupControllerTest extends AbstractIntegrationTest
{
    /**
     * @test
     */
    public function itWarmsupCacheEntries()
    {
        if (!class_exists('Zend\Mvc\Console\Controller\AbstractConsoleController')) {
            $this->markTestSkipped('Skipped because zend console for zend version 3 is not installed');
        }

        $config = require __DIR__ . '/../../resources/config.php';

        $serviceManager = $this->getServiceManager($config);

        $cache = $this->prophesize(StorageInterface::class);
        $cache->setItem(Argument::type('string'), Argument::type('array'))
            ->willReturn(true)
            ->shouldBeCalled();

        $controller = new CacheWarmupController(
            $config['service_manager'],
            $serviceManager->get(ExtractorInterface::class),
            $serviceManager->get(ResolverService::class),
            $cache->reveal()
        );

        $console = $this->prophesize(AdapterInterface::class);
        $controller->setConsole($console->reveal());

        $controller->indexAction();
    }
}
