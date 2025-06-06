<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitSelfCallRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\RemoveDataProviderParamKeysRector;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__,
    ])
    ->withSkipPath(__DIR__ . '/src/Extension/Rector/Set/config')
    ->withSkipPath('vendor')
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        rectorPreset: true,
        phpunitCodeQuality: true,
        doctrineCodeQuality: true,
        symfonyCodeQuality: true,
        symfonyConfigs: true,
    )
    ->withAttributesSets(
        all: true,
    )
    ->withSets([
        PHPUnitSetList::PHPUNIT_120,
    ])
    ->withImportNames(
        removeUnusedImports: true,
    )
    ->withSkip([
        PreferPHPUnitThisCallRector::class,
        StringClassNameToClassConstantRector::class,
    ])
    ->withSkip([
        RemoveDataProviderParamKeysRector::class => [__DIR__ . '/test'],
        RemoveEmptyClassMethodRector::class => [ __DIR__ . '/test'],
        RemoveUnusedConstructorParamRector::class => [__DIR__ . '/test'],
        RemoveUnusedPromotedPropertyRector::class => [__DIR__ . '/test'],
        RemoveUnusedVariableAssignRector::class => [__DIR__ . '/test'],
    ])
    ->withRules([
        PreferPHPUnitSelfCallRector::class,
        ExplicitNullableParamTypeRector::class,
    ]);
