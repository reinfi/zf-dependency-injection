<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Reinfi\DependencyInjection\Extension\Rector\Set\ReinfiDependencyInjectionSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([ReinfiDependencyInjectionSetList::ANNOTATION_TO_ATTRIBUTE]);
};
