<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Extension\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringClassesResolver;
use Reinfi\DependencyInjection\Extension\PHPStan\Resolve\AutoWiringPossibleResolver;

final readonly class AutoWiringPossibleRule implements Rule
{
    public function __construct(
        private AutoWiringClassesResolver $autoWiringClassesResolver,
        private AutoWiringPossibleResolver $autoWiringPossibleResolver
    ) {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     * @return IdentifierRuleError[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->namespacedName === null) {
            return [];
        }

        if (! $this->autoWiringClassesResolver->isAutowired($node->namespacedName->toString())) {
            return [];
        }

        try {
            $this->autoWiringPossibleResolver->resolve($node->namespacedName->toString());
        } catch (AutoWiringNotPossibleException $autoWiringNotPossibleException) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'AutoWiring of %s not possible, due to: %s',
                    $node->namespacedName->toString(),
                    $autoWiringNotPossibleException->getMessage()
                ))->identifier('autowiring.notPossible')->build(),
            ];
        }

        return [];
    }
}
