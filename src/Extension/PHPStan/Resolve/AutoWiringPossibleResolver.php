<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan\Resolve;

use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverServiceInterface;
use Reinfi\DependencyInjection\Extension\PHPStan\ServiceManagerLoader;

class AutoWiringPossibleResolver
{
    private ServiceManagerLoader $serviceManagerLoader;

    public function __construct(ServiceManagerLoader $serviceManagerLoader)
    {
        $this->serviceManagerLoader = $serviceManagerLoader;
    }

    public function resolve(string $className): void
    {
        $resolverService = $this->getResolverService();

        if ($resolverService === null) {
            return;
        }

        $resolverService->resolve($className);
    }

    private function getResolverService(): ?ResolverServiceInterface
    {
        return $this->serviceManagerLoader->getServiceLocator()?->get(ResolverService::class);
    }
}
