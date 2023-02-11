<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

class ExtractorChain implements ExtractorInterface
{
    /**
     * @var array<int, ExtractorInterface>
     */
    private array $chain;

    public function __construct(array $chain)
    {
        $this->chain = $chain;
    }

    public function getPropertiesInjections(string $className): array
    {
        return array_reduce(
            $this->chain,
            function (array $injections, ExtractorInterface $extractor) use ($className): array {
                if (count($injections) > 0) {
                    return $injections;
                }

                return $injections + $extractor->getPropertiesInjections($className);
            },
            []
        );
    }

    public function getConstructorInjections(string $className): array
    {
        return array_reduce(
            $this->chain,
            function (array $injections, ExtractorInterface $extractor) use ($className): array {
                if (count($injections) > 0) {
                    return $injections;
                }

                return $injections + $extractor->getConstructorInjections($className);
            },
            []
        );
    }
}
