<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectDoctrineRepository;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectDoctrineRepositoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getAnnotationValuesWithoutEntityManager
     */
    public function testItGetsRepositoryWithoutEntityManagerSet(
        array $values,
        string $repositoryClass
    ): void {
        $inject = new InjectDoctrineRepository($values);

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
     * @dataProvider getAnnotationValuesWithEntityManager
     */
    public function testItGetsRepositoryWithEntityManagerSet(
        array $values,
        string $entityManagerIdentifier,
        string $repositoryClass
    ): void {
        $inject = new InjectDoctrineRepository($values);

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
     * @dataProvider getAnnotationValuesWithoutEntityManager
     */
    public function testItGetsRepositoryFromPluginManager(
        array $values,
        string $repositoryClass
    ): void {
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

        self::assertInstanceOf(
            $repositoryClass,
            $inject($pluginManager->reveal()),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    public static function getAnnotationValuesWithoutEntityManager(): array
    {
        return [
            [
                [
                    'value' => EntityRepository::class,
                ],
                EntityRepository::class,
            ],
            [
                [
                    'entity' => EntityRepository::class,
                ],
                EntityRepository::class,
            ],
        ];
    }

    public static function getAnnotationValuesWithEntityManager(): array
    {
        return [
            [
                [
                    'entity' => EntityRepository::class,
                    'em' => 'doctrine.entityManager',
                ],
                'doctrine.entityManager',
                EntityRepository::class,
            ],
            [
                [
                    'entity' => EntityRepository::class,
                    'entityManager' => 'doctrine.entityManager',
                ],
                'doctrine.entityManager',
                EntityRepository::class,
            ],
        ];
    }
}
