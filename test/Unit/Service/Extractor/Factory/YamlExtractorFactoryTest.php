<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory;

use Laminas\Config\Config;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\Extractor\Factory\YamlExtractorFactory;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor\Factory
 */
final class YamlExtractorFactoryTest extends TestCase
{
    public function testItReturnsYamlExtractor(): void
    {
        $moduleConfig = new Config([
            'extractor_options' => [
                'file' => '',
            ],
        ]);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->with(ModuleConfig::class)
            ->willReturn($moduleConfig);

        $yamlExtractorFactory = new YamlExtractorFactory();

        self::assertInstanceOf(YamlExtractor::class, $yamlExtractorFactory($container));
    }
}
