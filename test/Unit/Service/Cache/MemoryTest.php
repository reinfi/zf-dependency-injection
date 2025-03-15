<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Cache;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Service\Cache\Memory;

class MemoryTest extends TestCase
{
    public function testItStoresCachedContents(): void
    {
        $cache = new Memory();

        self::assertTrue($cache->set('test', 'cachedValue'), 'Cache set should return true');

        self::assertTrue($cache->has('test'), 'Cache has should return true');

        self::assertEquals('cachedValue', $cache->get('test'), 'Cache get should return stored value');
    }

    public function testItHandlesNotStoredContents(): void
    {
        $cache = new Memory();

        self::assertFalse($cache->has('test'), 'Cache has should return false if not stored');

        self::assertNull($cache->get('test'), 'Cache get should return default value for not stored value');
    }

    public function testItDeletesStoredContents(): void
    {
        $cache = new Memory();

        $cache->set('test', 'cachedValue');

        self::assertTrue($cache->has('test'), 'Cache has should return true');

        self::assertTrue($cache->delete('test'), 'Cache get should return stored value');

        self::assertFalse($cache->has('test'), 'Cache has should return false for deleted item');
    }

    public function testItClearsStoredContents(): void
    {
        $cache = new Memory();

        $cache->set('test', 'cachedValue');
        $cache->set('test2', 'cachedValue');

        self::assertTrue($cache->has('test'), 'Cache has should return true');

        self::assertTrue($cache->clear(), 'Cache get should return stored value');

        self::assertFalse($cache->has('test'), 'Cache has should return false for cleared items');

        self::assertFalse($cache->has('test2'), 'Cache has should return false for cleared items');
    }

    #[DataProvider('badMethodDataProvider')]
    public function testItThrowsExceptionForNotImplementedMethods(string $methodName, array $methodParams): void
    {
        $this->expectException(BadMethodCallException::class);

        $cache = new Memory();

        call_user_func([$cache, $methodName], $methodParams);
    }

    public static function badMethodDataProvider(): array
    {
        return [
            [
                'methodName' => 'getMultiple',
                'methodParams' => [[]],
            ],
            [
                'methodName' => 'setMultiple',
                'methodParams' => [[]],
            ],
            [
                'methodName' => 'deleteMultiple',
                'methodParams' => [[]],
            ],
        ];
    }
}
