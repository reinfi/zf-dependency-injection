<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;

/**
 * @package Reinfi\DependencyInjection\Annotation
 * @deprecated 7.0.0 Use attributes from \Reinfi\DependencyInjection\Attribute namespace instead. Will be removed in 8.0.0.
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectDoctrineRepository extends AbstractAnnotation
{
    private readonly string $entityManager;

    private readonly string $entity;

    public function __construct(array $values)
    {
        if (! isset($values['value'])) {
            $this->entityManager = $values['entityManager'] ?? $values['em'] ?? 'Doctrine\ORM\EntityManager';
            $this->entity = $values['entity'];
            return;
        }

        $this->entity = $values['value'];
        $this->entityManager = 'Doctrine\ORM\EntityManager';
    }

    public function __invoke(ContainerInterface $container): EntityRepository
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
