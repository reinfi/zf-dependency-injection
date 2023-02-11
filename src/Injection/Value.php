<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class Value implements InjectionInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __invoke(ContainerInterface $container)
    {
        return $this->value;
    }
}
