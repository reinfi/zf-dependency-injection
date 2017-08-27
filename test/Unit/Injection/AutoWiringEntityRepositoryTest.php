<?php

namespace Reinfi\DependencyInjection\Unit\Injection;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\AutoWiringEntityRepository;

/**
 * @package Reinfi\DependencyInjection\Unit\Injection
 */
class AutoWiringEntityRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsRepositoryFromDefaultEntityManagerClass()
    {
        $repository = $this->prophesize(EntityRepository::class);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository('MyRepository')
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('Doctrine\ORM\EntityManager')
            ->willReturn($entityManager->reveal());

        $injection = new AutoWiringEntityRepository(
            'MyRepository'
        );

        $this->assertInstanceOf(
            EntityRepository::class,
            $injection($container->reveal())
        );
    }

    /**
     * @test
     */
    public function itReturnsRepositoryFromEntityManagerClass()
    {
        $repository = $this->prophesize(EntityRepository::class);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository('MyRepository')
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('MyEntityManager')
            ->willReturn($entityManager->reveal());

        $injection = new AutoWiringEntityRepository(
            'MyRepository',
            'MyEntityManager'
        );

        $this->assertInstanceOf(
            EntityRepository::class,
            $injection($container->reveal())
        );
    }
}