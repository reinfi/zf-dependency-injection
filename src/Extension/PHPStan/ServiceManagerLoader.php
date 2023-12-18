<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\ServiceManager;
use PHPStan\ShouldNotHappenException;

final class ServiceManagerLoader
{
    private ?ServiceManager $serviceLocator = null;

    public function __construct(?string $serviceManagerLoader)
    {
        if ($serviceManagerLoader === null) {
            return;
        }

        if (! \file_exists($serviceManagerLoader) || ! \is_readable($serviceManagerLoader)) {
            throw new ShouldNotHappenException('Service manager could not be loaded');
        }

        $serviceManager = require $serviceManagerLoader;
        if (! $serviceManager instanceof ServiceManager) {
            throw new ShouldNotHappenException(\sprintf(
                'Loader "%s" doesn\'t return a ServiceManager instance',
                $serviceManagerLoader
            ));
        }

        $this->serviceLocator = $serviceManager;
    }

    public function getServiceLocator(): ?ServiceLocatorInterface
    {
        return $this->serviceLocator;
    }
}
