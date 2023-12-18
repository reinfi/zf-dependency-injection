<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Exception;

use Exception;

class InjectionNotPossibleException extends Exception
{
    public static function fromUnknownPluginManager(string $pluginManager): self
    {
        return new self(sprintf('Could not resolve %s to a plugin manager instance', $pluginManager));
    }
}
