<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Iterator;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectDoctrineRepository;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Test\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
final class InjectDoctrineRepositoryTest extends TestCase
{
    #[DataProvider('getAttributeValuesWithoutEntityManager')]
    public function testItGetsRepositoryWithoutEntityManagerSet(array $values, string $repositoryClass): void
    {
        $injectDoctrineRepository = new InjectDoctrineRepository(...array_values($values));

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

    #[DataProvider('getAttributeValuesWithEntityManager')]
    public function testItGetsRepositoryWithEntityManagerSet(
        array $values,
        string $entityManagerIdentifier,
        string $repositoryClass
    ): void {
        $injectDoctrineRepository = new InjectDoctrineRepository(...array_values($values));

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

    #[DataProvider('getAttributeValuesWithoutEntityManager')]
    public function testItGetsRepositoryFromPluginManager(array $values, string $repositoryClass): void
    {
        $injectDoctrineRepository = new InjectDoctrineRepository(...array_values($values));

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

    public static function getAttributeValuesWithoutEntityManager(): Iterator
    {
        yield [
            [
                'entity' => EntityRepository::class,
            ],
            EntityRepository::class,
        ];
    }

    public static function getAttributeValuesWithEntityManager(): Iterator
    {
        yield [
            [
                'entity' => EntityRepository::class,
                'entityManager' => 'doctrine.entityManager',
            ],
            'doctrine.entityManager',
            EntityRepository::class,
        ];
    }

    public function testItThrowsExceptionIfEntityManagerIsNotAnObject(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $injectDoctrineRepository = new InjectDoctrineRepository(EntityRepository::class, 'No-EntityManager');

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('No-EntityManager')
            ->willReturn('1');

        $injectDoctrineRepository($container);
    }

    public function testItThrowsExceptionIfEntityManagerHasNotGetRepositoryMethod(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $injectDoctrineRepository = new InjectDoctrineRepository(EntityRepository::class, 'No-EntityManager');

        $service2 = $this->createMock(Service2::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('get')
            ->with('No-EntityManager')
            ->willReturn($service2);

        $injectDoctrineRepository($container);
    }
}
