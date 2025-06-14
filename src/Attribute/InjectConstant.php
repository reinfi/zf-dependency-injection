<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final readonly class InjectConstant implements InjectionInterface
{
    public function __construct(
        private string $value
    ) {
    }

    public function __invoke(ContainerInterface $container): mixed
    {
        return constant($this->value);
    }
}
