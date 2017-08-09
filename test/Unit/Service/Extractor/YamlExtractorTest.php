<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Service\ConfigService;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Service\InjectionService;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\Extractor
 */
class YamlExtractorTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnInjections()
    {
        $extractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $extractor->getConstructorInjections(InjectionService::class);

        $this->assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    /**
     * @test
     */
    public function itShouldReturnNoInjectionsIfNotDefined()
    {
        $extractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $extractor->getConstructorInjections(ConfigService::class);

        $this->assertCount(0, $injections);
    }
}