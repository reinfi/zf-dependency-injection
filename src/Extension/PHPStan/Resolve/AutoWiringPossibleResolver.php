<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan\Resolve;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Reinfi\DependencyInjection\Extension\PHPStan\ServiceManagerLoader;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;

class AutoWiringPossibleResolver
{
    public function __construct(
        private readonly ServiceManagerLoader $serviceManagerLoader
    ) {
    }

    public function resolve(string $className): void
    {
        $resolverService = $this->getResolverService();

        if (! $resolverService instanceof ResolverServiceInterface) {
            return;
        }

        $resolverService->resolve($className);
    }

    private function getResolverService(): ?ResolverServiceInterface
    {
        $serviceLocator = $this->serviceManagerLoader->getServiceLocator();

        if (! $serviceLocator instanceof ServiceLocatorInterface) {
            return null;
        }

        return $serviceLocator->get(ResolverService::class);
    }
}
