<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Reinfi\DependencyInjection\Service\CacheService;
use stdClass;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
final class CacheServiceTest extends TestCase
{
    #[DataProvider('getMethodDataProvider')]
    public function testItProxiesCallToUnderlyingCache(
        string $method,
        array $arguments,
        array $params,
        mixed $returnValue,
        mixed $expectedResult
    ): void {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())
            ->method($method)
            ->with(...$arguments)
            ->willReturn($returnValue);

        $cacheService = new CacheService($cache);

        $result = call_user_func_array([$cacheService, $method], $params);

        self::assertSame($expectedResult, $result);
    }

    public static function getMethodDataProvider(): array
    {
        return [
            'get method returns array' => ['get', ['itemKey'], ['itemKey'], ['cachedItem'], ['cachedItem']],
            'get method returns null for non-array' => ['get', ['itemKey'], ['itemKey'], 'not an array', null],
            'has method' => ['has', ['itemKey'], ['itemKey'], true, true],
            'set method' => ['set', ['itemKey', ['itemValue']], ['itemKey', ['itemValue']], true, true],
        ];
    }

    public function testItReturnsNullIfCacheValueIsNotAnArray(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())
            ->method('get')
            ->with('key')
            ->willReturn(new stdClass());

        $cacheService = new CacheService($cache);

        self::assertNull($cacheService->get('key'));
    }
}
