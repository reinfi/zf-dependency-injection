<?php

namespace Reinfi\DependencyInjection\Annotation;

use Psr\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectDoctrineRepository implements AnnotationInterface
{
    /**
     * @var string
     */
    private $entityManager = 'Doctrine\ORM\EntityManager';

    /**
     * @var string
     */
    private $entity;

    /**
     * @param $values
     */
    public function __construct(array $values)
    {
        if (!isset($values['value'])) {
            if (isset($values['em'])) {
                $this->entityManager = $values['em'];
            }

            $this->entity = $values['entity'];

            return;
        }


        $this->entity = $values['value'];
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container instanceof AbstractPluginManager) {
            return $container
                ->getServiceLocator()
                ->get($this->entityManager)
                ->getRepository($this->entity);
        }

        return $container->get($this->entityManager)->getRepository($this->entity);
    }
}