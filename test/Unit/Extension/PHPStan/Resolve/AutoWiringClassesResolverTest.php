<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Extension\PHPStan\Resolve;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringClassesResolver;
use Reinfi\DependencyInjection\Extension\PHPStan\ServiceManagerLoader;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Reinfi\DependencyInjection\Test\Service\Service2;

final class AutoWiringClassesResolverTest extends TestCase
{
    public function testItReturnsFalseIfNoServiceManagerIsProvided(): void
    {
        $serviceManagerLoader = new ServiceManagerLoader(null);

        $autoWiringClassesResolver = new AutoWiringClassesResolver($serviceManagerLoader);

        self::assertFalse($autoWiringClassesResolver->isAutowired(Service1::class));
    }

    public function testItReturnsTrueIfClassIsRegisteredForAutoWiring(): void
    {
        $serviceManagerLoader = new ServiceManagerLoader(__DIR__ . '/../../../../resources/container.php');

        $autoWiringClassesResolver = new AutoWiringClassesResolver($serviceManagerLoader);

        self::assertTrue($autoWiringClassesResolver->isAutowired(Service1::class));

        self::assertFalse($autoWiringClassesResolver->isAutowired(Service2::class));
    }
}
