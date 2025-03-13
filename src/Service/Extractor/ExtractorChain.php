<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

class ExtractorChain implements ExtractorInterface
{
    /**
     * @var array<ExtractorInterface>
     */
    private array $chain;

    /**
     * @param array<ExtractorInterface> $chain
     */
    public function __construct(array $chain)
    {
        $this->chain = $chain;
    }

    /**
     * @return InjectionInterface[]
     */
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

    /**
     * @return InjectionInterface[]
     */
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
