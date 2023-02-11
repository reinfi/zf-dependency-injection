<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan\Resolve;

use Laminas\Config\Config;
use Reinfi\DependencyInjection\Extension\PHPStan\ServiceManagerLoader;
use Reinfi\DependencyInjection\Factory\AutoWiringFactory;

class AutoWiringClassesResolver
{
    private ServiceManagerLoader $serviceManagerLoader;

    /**
     * @var string[]|null
     */
    private ?array $autowiredClasses = null;

    public function __construct(ServiceManagerLoader $serviceManagerLoader)
    {
        $this->serviceManagerLoader = $serviceManagerLoader;
    }

    public function isAutowired(string $className): bool
    {
        if ($this->autowiredClasses === null) {
            $this->autowiredClasses = $this->findAutowiredClasses();
        }

        return in_array($className, $this->autowiredClasses, true);
    }

    private function findAutowiredClasses(): array
    {
        $serviceManager = $this->serviceManagerLoader->getServiceLocator();

        if ($serviceManager === null) {
            return [];
        }

        $config = $serviceManager->get('config');

        if ($config instanceof Config) {
            $config = $config->toArray();
        }

        if (! is_array($config)) {
            return [];
        }

        return array_reduce(
            $config,
            function (array $classes, $config): array {
                if (! is_array($config)) {
                    return $classes;
                }

                $factories = $config['factories'] ?? null;
                if (! is_array($factories)) {
                    return $classes;
                }

                $autowiredClasses = array_filter(
                    $factories,
                    function ($factoryClass): bool {
                        return $factoryClass === AutoWiringFactory::class;
                    }
                );

                return array_merge($classes, array_keys($autowiredClasses));
            },
            []
        );
    }
}
