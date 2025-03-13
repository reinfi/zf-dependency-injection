<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class InjectDoctrineRepository extends AbstractAttribute
{
    public function __construct(
        /**
         * @var class-string<object>
         */
        private readonly string $entity,
        private readonly ?string $entityManager = null
    ) {
    }

    public function __invoke(ContainerInterface $container): EntityRepository
    {
        $container = $this->determineContainer($container);

        $entityManager = $container->get($this->entityManager ?? 'Doctrine\ORM\EntityManager');

        if (
            ! is_object($entityManager)
            || ! method_exists($entityManager, 'getRepository')
            || ! is_a($entityManager, 'Doctrine\ORM\EntityManagerInterface')
        ) {
            throw new AutoWiringNotPossibleException($this->entity);
        }

        return $entityManager->getRepository($this->entity);
    }
}
