<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Iterator;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectDoctrineRepository;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
final class InjectDoctrineRepositoryTest extends TestCase
{
    #[DataProvider('getAnnotationValuesWithoutEntityManager')]
    public function testItGetsRepositoryWithoutEntityManagerSet(array $values, string $repositoryClass): void
    {
        $injectDoctrineRepository = new InjectDoctrineRepository($values);

        $mockObject = $this->createMock($repositoryClass);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($repositoryClass)
            ->willReturn($mockObject);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('Doctrine\ORM\EntityManager')
            ->willReturn($entityManager);

        self::assertInstanceOf(
            $repositoryClass,
            $injectDoctrineRepository($container),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    #[DataProvider('getAnnotationValuesWithEntityManager')]
    public function testItGetsRepositoryWithEntityManagerSet(
        array $values,
        string $entityManagerIdentifier,
        string $repositoryClass
    ): void {
        $injectDoctrineRepository = new InjectDoctrineRepository($values);

        $mockObject = $this->createMock($repositoryClass);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($repositoryClass)
            ->willReturn($mockObject);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with($entityManagerIdentifier)
            ->willReturn($entityManager);

        self::assertInstanceOf(
            $repositoryClass,
            $injectDoctrineRepository($container),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    /**
     * use PHPUnit\Framework\Attributes\DataProvider;
    #[DataProvider getAnnotationValuesWithoutEntityManager
     */
    #[DataProvider('getAnnotationValuesWithoutEntityManager')]
    public function testItGetsRepositoryFromPluginManager(array $values, string $repositoryClass): void
    {
        $injectDoctrineRepository = new InjectDoctrineRepository($values);

        $mockObject = $this->createMock($repositoryClass);

        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with($repositoryClass)
            ->willReturn($mockObject);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('Doctrine\ORM\EntityManager')
            ->willReturn($entityManager);

        $pluginManager = $this->createMock(AbstractPluginManager::class);
        $pluginManager->expects($this->once())
            ->method('getServiceLocator')
            ->willReturn($container);

        self::assertInstanceOf(
            $repositoryClass,
            $injectDoctrineRepository($pluginManager),
            'Should be instance of repositoryClass ' . $repositoryClass
        );
    }

    public static function getAnnotationValuesWithoutEntityManager(): Iterator
    {
        yield [
            [
                'value' => EntityRepository::class,
            ],
            EntityRepository::class,
        ];
        yield [
            [
                'entity' => EntityRepository::class,
            ],
            EntityRepository::class,
        ];
    }

    public static function getAnnotationValuesWithEntityManager(): Iterator
    {
        yield [
            [
                'entity' => EntityRepository::class,
                'em' => 'doctrine.entityManager',
            ],
            'doctrine.entityManager',
            EntityRepository::class,
        ];
        yield [
            [
                'entity' => EntityRepository::class,
                'entityManager' => 'doctrine.entityManager',
            ],
            'doctrine.entityManager',
            EntityRepository::class,
        ];
    }
}
