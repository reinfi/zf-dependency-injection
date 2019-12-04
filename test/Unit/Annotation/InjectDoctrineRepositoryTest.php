<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectDoctrineRepository;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectDoctrineRepositoryTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getAnnotationValuesWithoutEntityManager
     *
     * @param array  $values
     * @param string $repositoryClass
     */
    public function itGetsRepositoryWithoutEntityManagerSet(
        array $values,
        string $repositoryClass
    ) {
        $inject = new InjectDoctrineRepository($values);

        $repository = $this->prophesize($repositoryClass);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository($repositoryClass)
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('Doctrine\ORM\EntityManager')
            ->willReturn($entityManager->reveal());

        $this->assertInstanceOf(
            $repositoryClass,
            $inject($container->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @test
     *
     * @dataProvider getAnnotationValuesWithEntityManager
     *
     * @param array  $values
     * @param string $entityManagerIdentifier
     * @param string $repositoryClass
     */
    public function itGetsRepositoryWithEntityManagerSet(
        array $values,
        string $entityManagerIdentifier,
        string $repositoryClass
    ) {
        $inject = new InjectDoctrineRepository($values);

        $repository = $this->prophesize($repositoryClass);

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->getRepository($repositoryClass)
            ->willReturn($repository->reveal());

        $container = $this->prophesize(ContainerInterface::class);
        $container->get($entityManagerIdentifier)
            ->willReturn($entityManager->reveal());

        $this->assertInstanceOf(
            $repositoryClass,
            $inject($container->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @test
     *
     * @dataProvider getAnnotationValuesWithoutEntityManager
     *
     * @param array  $values
     * @param string $repositoryClass
     */
    public function itGetsRepositoryFromPluginManager(
        array $values,
        string $repositoryClass
    ) {
        $inject = new InjectDoctrineRepository($values);

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

        $this->assertInstanceOf(
            $repositoryClass,
            $inject($pluginManager->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @return array
     */
    public function getAnnotationValuesWithoutEntityManager(): array
    {
        return [
            [
                [ 'value' => EntityRepository::class ],
                EntityRepository::class,
            ],
            [
                [ 'entity' => EntityRepository::class ],
                EntityRepository::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAnnotationValuesWithEntityManager(): array
    {
        return [
            [
                [
                    'entity' => EntityRepository::class,
                    'em'     => 'doctrine.entityManager',
                ],
                'doctrine.entityManager',
                EntityRepository::class,
            ],
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
