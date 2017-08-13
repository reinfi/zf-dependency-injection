<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Service\InjectionService;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class AutoWiringServiceTest extends TestCase
{
    /**
     * @test
     */
    public function itResolvesConstructorArguments()
    {
        $resolverService = $this->prophesize(ResolverService::class);

        $resolverService->resolve(InjectionService::class)
            ->willReturn([
                new AutoWiring(ExtractorInterface::class),
                new AutoWiring(StorageInterface::class),
             ]);

        $service = new AutoWiringService(
            $resolverService->reveal(),
            new Memory()
        );

        $container = $this->prophesize(ContainerInterface::class);

        $container->has(ExtractorInterface::class)->willReturn(true);
        $container->get(ExtractorInterface::class)
            ->willReturn(
                $this->prophesize(ExtractorInterface::class)->reveal()
            );
        $container->has(StorageInterface::class)->willReturn(true);
        $container->get(StorageInterface::class)
            ->willReturn(new Memory());

        $injections = $service->resolveConstructorInjection(
            $container->reveal(),
            InjectionService::class
        );

        $this->assertCount(2, $injections);
    }
}