<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\MethodProphecy;
use Psr\SimpleCache\CacheInterface;
use Reinfi\DependencyInjection\Service\CacheService;
use stdClass;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class CacheServiceTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     *
     * @dataProvider getMethodDataProvider
     *
     * @param string $method
     * @param array  $arguments
     * @param mixed  $params
     * @param mixed  $returnValue
     */
    public function itProxiesCallToUnderlyingCache(
        string $method,
        array $arguments,
        $params,
        $returnValue
    ): void {
        $cache = $this->prophesize(CacheInterface::class);
        $methodProphecy = new MethodProphecy($cache, $method, $arguments);
        $methodProphecy->willReturn($returnValue);
        $cache->addMethodProphecy($methodProphecy);

        $service = new CacheService($cache->reveal());

        $result = call_user_func_array([$service, $method], $params);

        self::assertEquals(
            $returnValue,
            $result,
            'Return value ' . json_encode($result) . ' does not match expected ' . json_encode($returnValue)
        );
    }

    /**
     * @return array
     */
    public function getMethodDataProvider(): array
    {
        return [
            [
                'get',
                [ Argument::exact('itemKey'), Argument::exact(null), Argument::exact(null) ],
                [ 'itemKey' ],
                ['cachedItem'],
            ],
            [
                'has',
                [ Argument::exact('itemKey') ],
                [ 'itemKey' ],
                true,
            ],
            [
                'set',
                [ Argument::exact('itemKey'), Argument::exact(['itemValue']) ],
                [ 'itemKey', ['itemValue'] ],
                true,
            ],
        ];
    }

    public function itReturnsNullIfCacheValueIsNotAnArray(): void
    {
        $cache = $this->prophesize(CacheInterface::class);
        $cache->get('key')->willReturn(new stdClass());

        $cacheService = new CacheService($cache->reveal());

        $this->assertNull($cacheService->get('key'));
    }
}
