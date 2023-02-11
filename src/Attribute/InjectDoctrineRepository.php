<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Attribute;

use Attribute;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Attribute
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class InjectDoctrineRepository extends AbstractAttribute
{
    private string $entityManager = 'Doctrine\ORM\EntityManager';

    private string $entity;

    public function __construct(string $entity, ?string $entityManager = null)
    {
        $this->entity = $entity;

        if ($entityManager !== null) {
            $this->entityManager = $entityManager;
        }
    }

    public function __invoke(ContainerInterface $container)
    {
        $container = $this->determineContainer($container);

        $entityManager = $container->get($this->entityManager);

        if (
            ! is_object($entityManager)
            || ! method_exists($entityManager, 'getRepository')
        ) {
            throw new AutoWiringNotPossibleException($this->entity);
        }

        return $entityManager->getRepository($this->entity);
    }
}
