<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Attribute;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Laminas\ServiceManager\AbstractPluginManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Attribute\InjectDoctrineRepository;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Test\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Attribute
 */
class InjectDoctrineRepositoryTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider getAttributeValuesWithoutEntityManager
     */
    public function testItGetsRepositoryWithoutEntityManagerSet(array $values, string $repositoryClass): void
    {
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
     * @dataProvider getAttributeValuesWithEntityManager
     */
    public function testItGetsRepositoryWithEntityManagerSet(
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
     * @dataProvider getAttributeValuesWithoutEntityManager
     */
    public function testItGetsRepositoryFromPluginManager(array $values, string $repositoryClass): void
    {
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

    public static function getAttributeValuesWithoutEntityManager(): array
    {
        return [
            [
                [
                    'entity' => EntityRepository::class,
                ],
                EntityRepository::class,
            ],
        ];
    }

    public static function getAttributeValuesWithEntityManager(): array
    {
        return [
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

    public function testItThrowsExceptionIfEntityManagerIsNotAnObject(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $inject = new InjectDoctrineRepository(EntityRepository::class, 'No-EntityManager');

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('No-EntityManager')->willReturn('1');

        $inject($container->reveal());
    }

    public function testItThrowsExceptionIfEntityManagerHasNotGetRepositoryMethod(): void
    {
        $this->expectException(AutoWiringNotPossibleException::class);

        $inject = new InjectDoctrineRepository(EntityRepository::class, 'No-EntityManager');

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('No-EntityManager')->willReturn($this->prophesize(Service2::class)->reveal());

        $inject($container->reveal());
    }
}
