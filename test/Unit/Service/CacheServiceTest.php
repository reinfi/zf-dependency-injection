<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Reinfi\DependencyInjection\Service\CacheService;
use stdClass;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class CacheServiceTest extends TestCase
{
    /**
     * @dataProvider getMethodDataProvider
     */
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

        $service = new CacheService($cache);

        $result = call_user_func_array([$service, $method], $params);

        $this->assertSame($expectedResult, $result);
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

        $this->assertNull($cacheService->get('key'));
    }
}
