<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Service\Service1;
use Reinfi\DependencyInjection\Service\Service2;
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

        $injections = $extractor->getConstructorInjections(Service1::class);

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

        $injections = $extractor->getConstructorInjections(Service2::class);

        $this->assertCount(0, $injections);
    }
}