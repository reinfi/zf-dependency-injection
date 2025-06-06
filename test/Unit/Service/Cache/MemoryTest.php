<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Cache;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Service\Cache\Memory;

final class MemoryTest extends TestCase
{
    public function testItStoresCachedContents(): void
    {
        $memory = new Memory();

        self::assertTrue($memory->set('test', 'cachedValue'), 'Cache set should return true');

        self::assertTrue($memory->has('test'), 'Cache has should return true');

        self::assertEquals('cachedValue', $memory->get('test'), 'Cache get should return stored value');
    }

    public function testItHandlesNotStoredContents(): void
    {
        $memory = new Memory();

        self::assertFalse($memory->has('test'), 'Cache has should return false if not stored');

        self::assertNull($memory->get('test'), 'Cache get should return default value for not stored value');
    }

    public function testItDeletesStoredContents(): void
    {
        $memory = new Memory();

        $memory->set('test', 'cachedValue');

        self::assertTrue($memory->has('test'), 'Cache has should return true');

        self::assertTrue($memory->delete('test'), 'Cache get should return stored value');

        self::assertFalse($memory->has('test'), 'Cache has should return false for deleted item');
    }

    public function testItClearsStoredContents(): void
    {
        $memory = new Memory();

        $memory->set('test', 'cachedValue');
        $memory->set('test2', 'cachedValue');

        self::assertTrue($memory->has('test'), 'Cache has should return true');

        self::assertTrue($memory->clear(), 'Cache get should return stored value');

        self::assertFalse($memory->has('test'), 'Cache has should return false for cleared items');

        self::assertFalse($memory->has('test2'), 'Cache has should return false for cleared items');
    }

    #[DataProvider('badMethodDataProvider')]
    public function testItThrowsExceptionForNotImplementedMethods(string $methodName, array $methodParams): void
    {
        $this->expectException(BadMethodCallException::class);

        $memory = new Memory();

        call_user_func([$memory, $methodName], $methodParams);
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
