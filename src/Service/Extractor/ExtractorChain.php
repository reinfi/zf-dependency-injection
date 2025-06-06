<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

class ExtractorChain implements ExtractorInterface
{
    /**
     * @param array<ExtractorInterface> $chain
     */
    public function __construct(
        private readonly array $chain
    ) {
    }

    /**
     * @return InjectionInterface[]
     */
    public function getPropertiesInjections(string $className): array
    {
        return array_reduce(
            $this->chain,
            function (array $injections, ExtractorInterface $extractor) use ($className): array {
                if ($injections !== []) {
                    return $injections;
                }

                return $injections + $extractor->getPropertiesInjections($className);
            },
            []
        );
    }

    /**
     * @return InjectionInterface[]
     */
    public function getConstructorInjections(string $className): array
    {
        return array_reduce(
            $this->chain,
            function (array $injections, ExtractorInterface $extractor) use ($className): array {
                if ($injections !== []) {
                    return $injections;
                }

                return $injections + $extractor->getConstructorInjections($className);
            },
            []
        );
    }
}
