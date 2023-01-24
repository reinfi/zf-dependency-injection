<?php

declare(strict_types=1);

return (static function (): Psr\Container\ContainerInterface {
    return Laminas\Mvc\Application::init(require __DIR__ . '/application_config.php')
        ->getServiceManager();
})();
