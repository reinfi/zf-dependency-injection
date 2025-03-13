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
    private string $entityManager = 'Doctrine\ORM\EntityManager';

    private string $entity;

    public function __construct(array $values)
    {
        if (! isset($values['value'])) {
            if (isset($values['em']) || isset($values['entityManager'])) {
                $this->entityManager = $values['entityManager'] ?? $values['em'];
            }

            $this->entity = $values['entity'];

            return;
        }

        $this->entity = $values['value'];
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
