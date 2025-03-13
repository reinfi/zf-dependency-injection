<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
class LazyResolverService implements ResolverServiceInterface
{
    private ?ResolverServiceInterface $resolverService = null;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function resolve(string $className, ?array $options = null): array
    {
        if (! $this->resolverService instanceof ResolverServiceInterface) {
            $this->resolverService = $this->container->get(ResolverService::class);
        }

        return $this->resolverService->resolve($className, $options);
    }
}
