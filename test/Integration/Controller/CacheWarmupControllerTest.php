<?php

namespace Reinfi\DependencyInjection\Integration\Controller;

use Prophecy\Argument;
use Reinfi\DependencyInjection\Controller\CacheWarmupController;
use Reinfi\DependencyInjection\Integration\AbstractIntegrationTest;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\Console\Adapter\AdapterInterface;

/**
 * @package Reinfi\DependencyInjection\Integration\Controller
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
        if (!class_exists('Zend\Mvc\Controller\AbstractConsoleController')) {
            $this->markTestSkipped('Skipped because zend console is removed within zend version 3');
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