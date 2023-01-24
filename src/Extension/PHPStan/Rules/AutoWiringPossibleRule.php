<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringClassesResolver;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringPossibleResolver;

final class AutoWiringPossibleRule implements Rule
{
    private AutoWiringClassesResolver $classesResolver;
    private AutoWiringPossibleResolver $possibleResolver;

    public function __construct(AutoWiringClassesResolver $classesResolver, AutoWiringPossibleResolver $possibleResolver)
    {
        $this->classesResolver = $classesResolver;
        $this->possibleResolver = $possibleResolver;
    }

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    /**
     * @param Node\Stmt\Class_ $node
     * @param Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->name === null) {
            return [];
        }

        if (! $this->classesResolver->isAutowired($node->name->name)) {
            return [];
        }

        try {
            $this->possibleResolver->resolve($node->name->name);
        } catch (AutoWiringNotPossibleException $exception) {
            return [
                $exception->getMessage()
            ];
        }

        return [];
    }
}
