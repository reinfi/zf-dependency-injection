<?php

declare(strict_types=1);

use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    // make ECS 16x faster
    $ecsConfig->parallel();

    $ecsConfig->paths([
        __DIR__ . '/config/',
        __DIR__ . '/src/',
        __DIR__ . '/test/',
    ]);

    // import SetList here in the end of ecs. is on purpose
    // to avoid overridden by existing Skip Option in current config
    $ecsConfig->import(SetList::PSR_12);
    $ecsConfig->import(SetList::COMMON);
    $ecsConfig->import(SetList::NAMESPACES);
    $ecsConfig->import(SetList::CLEAN_CODE);

    $ecsConfig->rule(LineLengthFixer::class);

    $ecsConfig->lineEnding("\n");
};
