<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectDoctrineRepository;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectDoctrineRepositoryTest extends TestCase
{
    /**
     * @dataProvider getAnnotationValuesWithoutEntityManager
     */
    public function testItGetsRepositoryWithoutEntityManagerSet(array $values, string $repositoryClass): void
    {
        $inject = new InjectDoctrineRepository($values);

        $repository = $this->createMock($repositoryClass);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo($repositoryClass))
            ->willReturn($repository);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('Doctrine\ORM\EntityManager'))
            ->willReturn($entityManager);

        self::assertInstanceOf(
            $repositoryClass,
            $inject($container),
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

        $repository = $this->createMock($repositoryClass);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo($repositoryClass))
            ->willReturn($repository);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo($entityManagerIdentifier))
            ->willReturn($entityManager);

        self::assertInstanceOf(
            $repositoryClass,
            $inject($container),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * @dataProvider getAnnotationValuesWithoutEntityManager
     */
    public function testItGetsRepositoryFromPluginManager(array $values, string $repositoryClass): void
    {
        $inject = new InjectDoctrineRepository($values);

        $repository = $this->createMock($repositoryClass);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($this->equalTo($repositoryClass))
            ->willReturn($repository);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($this->equalTo('Doctrine\ORM\EntityManager'))
            ->willReturn($entityManager);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertInstanceOf(
            $repositoryClass,
            $inject($pluginManager),
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
