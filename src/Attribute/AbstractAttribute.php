<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Reinfi\DependencyInjection\Injection\ContainerTrait;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
abstract class AbstractAttribute implements InjectionInterface
{
    use ContainerTrait;
}
