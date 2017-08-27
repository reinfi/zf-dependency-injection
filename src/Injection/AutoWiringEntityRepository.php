<?php

namespace Reinfi\DependencyInjection\Injection;

use Psr\Container\ContainerInterface;

/**
 * @package Reinfi\DependencyInjection\Injection
 */
class AutoWiringEntityRepository implements InjectionInterface
{
    /**
     * @var string
     */
    private $repositoryClass;

    /**
     * @var string
     */
    private $entityManagerClass;

    /**
     * @param string $repositoryClass
     * @param string $entityManagerClass
     */
    public function __construct(
        string $repositoryClass,
        string $entityManagerClass = 'Doctrine\ORM\EntityManager'
    ) {
        $this->repositoryClass = $repositoryClass;
        $this->entityManagerClass = $entityManagerClass;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        return $container
            ->get($this->entityManagerClass)
            ->getRepository($this->repositoryClass);
    }
}