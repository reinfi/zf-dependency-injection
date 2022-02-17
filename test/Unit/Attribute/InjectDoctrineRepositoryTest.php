<?php

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectDoctrineRepository;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectDoctrineRepositoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     *
     * @dataProvider getAttributeValuesWithoutEntityManager
     *
     * @param array  $values
     * @param string $repositoryClass
     */
    public function itGetsRepositoryWithoutEntityManagerSet(
        array $values,
        string $repositoryClass
    ): void {
        $inject = new InjectDoctrineRepository(...array_values($values));

        $repository = $this->prophesize($repositoryClass);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository($repositoryClass)
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('Doctrine\ORM\EntityManager')
            ->willReturn($entityManager->reveal());

        self::assertInstanceOf(
            $repositoryClass,
            $inject($container->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @test
     *
     * @dataProvider getAttributeValuesWithEntityManager
     *
     * @param array  $values
     * @param string $entityManagerIdentifier
     * @param string $repositoryClass
     */
    public function itGetsRepositoryWithEntityManagerSet(
        array $values,
        string $entityManagerIdentifier,
        string $repositoryClass
    ): void {
        $inject = new InjectDoctrineRepository(...array_values($values));

        $repository = $this->prophesize($repositoryClass);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository($repositoryClass)
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($entityManagerIdentifier)
            ->willReturn($entityManager->reveal());

        self::assertInstanceOf(
            $repositoryClass,
            $inject($container->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @test
     *
     * @dataProvider getAttributeValuesWithoutEntityManager
     *
     * @param array  $values
     * @param string $repositoryClass
     */
    public function itGetsRepositoryFromPluginManager(
        array $values,
        string $repositoryClass
    ): void {
        $inject = new InjectDoctrineRepository(...array_values($values));

        $repository = $this->prophesize($repositoryClass);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository($repositoryClass)
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('Doctrine\ORM\EntityManager')
            ->willReturn($entityManager->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        self::assertInstanceOf(
            $repositoryClass,
            $inject($pluginManager->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @return array
     */
    public function getAttributeValuesWithoutEntityManager(): array
    {
        return [
            [
                [ 'entity' => EntityRepository::class ],
                EntityRepository::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAttributeValuesWithEntityManager(): array
    {
        return [
            [
                [
                    'entity'        => EntityRepository::class,
                    'entityManager' => 'doctrine.entityManager',
                ],
                'doctrine.entityManager',
                EntityRepository::class,
            ],
        ];
    }
}
