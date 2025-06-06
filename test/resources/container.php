<?php

declare(strict_types=1);

use Laminas\Mvc\Application;
use Psr\Container\ContainerInterface;

return (static fn (): ContainerInterface => Application::init(require __DIR__ . '/application_config.php')
    ->getServiceManager())();
