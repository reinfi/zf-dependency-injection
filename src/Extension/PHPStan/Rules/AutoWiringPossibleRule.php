<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringClassesResolver;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringPossibleResolver;

final class AutoWiringPossibleRule implements Rule
{
    private AutoWiringClassesResolver $classesResolver;

    private AutoWiringPossibleResolver $possibleResolver;

    public function __construct(
        AutoWiringClassesResolver $classesResolver,
        AutoWiringPossibleResolver $possibleResolver
    ) {
        $this->classesResolver = $classesResolver;
        $this->possibleResolver = $possibleResolver;
    }

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    /**
     * @param Node\Stmt\Class_ $node
     * @return IdentifierRuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->namespacedName === null) {
            return [];
        }

        if (! $this->classesResolver->isAutowired($node->namespacedName->toString())) {
            return [];
        }

        try {
            $this->possibleResolver->resolve($node->namespacedName->toString());
        } catch (AutoWiringNotPossibleException $exception) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'AutoWiring of %s not possible, due to: %s',
                    $node->namespacedName->toString(),
                    $exception->getMessage()
                ))->identifier('autowiring.notPossible')->build(),
            ];
        }

        return [];
    }
}
