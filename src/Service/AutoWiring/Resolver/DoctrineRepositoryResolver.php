<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;

use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiringEntityRepository;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class DoctrineRepositoryResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter)
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        $reflClass = $parameter->getClass();

        $parentClass = $reflClass->getParentClass();

        if ($parentClass === null || $reflClass->isInterface()) {
            return null;
        }

        if ($parentClass->getName() === 'Doctrine\ORM\EntityRepository') {
            return new AutoWiringEntityRepository($reflClass->getName());
        }

        $interfaceNames = $reflClass->getInterfaceNames();

        if (in_array('Doctrine\Common\Persistence\ObjectRepository', $interfaceNames)) {
            return new AutoWiringEntityRepository($reflClass->getName());
        }

        return null;
    }
}