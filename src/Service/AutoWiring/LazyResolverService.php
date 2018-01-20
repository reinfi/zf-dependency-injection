<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use Psr\Container\ContainerInterface;

/**
 * Class LazyResolverService
 *
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 * @author Martin Rintelen <martin.rintelen@check24.de>
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
    public function resolve(string $className): array
    {
        if (!$this->resolverService instanceof ResolverServiceInterface) {
            $this->resolverService = $this->container->get(ResolverService::class);
        }

        return $this->resolverService->resolve($className);
    }
}
