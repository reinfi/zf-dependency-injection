<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Exception\InjectionTypeUnknownException;
use Reinfi\DependencyInjection\Service\Extractor\YamlExtractor;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;
use Symfony\Component\Yaml\Yaml;

/**
 * @package Reinfi\DependencyInjection\Test\Test\Unit\Service\Extractor
 */
final class YamlExtractorTest extends TestCase
{
    public function testItShouldReturnEmptyArrayForPropertyInjections(): void
    {
        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $yamlExtractor->getPropertiesInjections(Service1::class);

        self::assertCount(0, $injections);
    }

    public function testItShouldReturnInjections(): void
    {
        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $yamlExtractor->getConstructorInjections(Service1::class);

        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItShouldSetRequiredInjectionProperties(): void
    {
        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $yamlExtractor->getConstructorInjections(Service1::class);

        self::assertEquals(
            Service2::class,
            $injections[0]->value,
            'First injection should be of type ' . Service2::class
        );
    }

    public function testItShouldReturnInjectionsIfTypeHasConstructorArguments(): void
    {
        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $yamlExtractor->getConstructorInjections('Reinfi\DependencyInjection\Service\ServiceDoctrine');

        self::assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testItShouldReturnNoInjectionsIfNotDefined(): void
    {
        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $injections = $yamlExtractor->getConstructorInjections(Service2::class);

        self::assertCount(0, $injections);
    }

    public function testItThrowsExceptionIfConfigurationKeyTypeMisses(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/bad_services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $yamlExtractor->getConstructorInjections(Service1::class);
    }

    public function testItThrowsExceptionIfTypeIsUnknown(): void
    {
        $this->expectException(InjectionTypeUnknownException::class);

        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/bad_services.yml',
            'Reinfi\DependencyInjection\Annotation'
        );

        $yamlExtractor->getConstructorInjections(Service2::class);
    }

    public function testItThrowsExceptionIfTypeIsNotOfTypeInjectionInterface(): void
    {
        $this->expectException(InjectionTypeUnknownException::class);

        $yamlExtractor = new YamlExtractor(
            new Yaml(),
            __DIR__ . '/../../../resources/bad_services.yml',
            'Reinfi\DependencyInjection\Test\Service'
        );

        $yamlExtractor->getConstructorInjections(ServiceAnnotation::class);
    }
}
