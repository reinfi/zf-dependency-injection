<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\Factory\YamlExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Zend\Config\Config;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory
 */
class YamlExtractorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsYamlExtractor()
    {
        $moduleConfig = new Config(['extractor_options' => [ 'file' => '' ]]);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(ModuleConfig::class)
            ->willReturn($moduleConfig);

        $factory = new YamlExtractorFactory();

        $this->assertInstanceOf(
            YamlExtractor::class,
            $factory($container->reveal())
        );
    }
}
