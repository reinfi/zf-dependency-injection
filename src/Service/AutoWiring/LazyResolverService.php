<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use Psr\Container\ContainerInterface;

/**
 * Class LazyResolverService
 *
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
class LazyResolverService implements ResolverServiceInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ResolverServiceInterface
     */
    private $resolverService;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function resolve(string $className, ?array $options = null): array
    {
        if (!$this->resolverService instanceof ResolverServiceInterface) {
            $this->resolverService = $this->container->get(ResolverService::class);
        }

        return $this->resolverService->resolve($className, $options);
    }
}
