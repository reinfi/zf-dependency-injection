<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Reinfi\DependencyInjection\Service\CacheService;
use Laminas\Cache\Storage\StorageInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service
 */
class CacheServiceTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
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
    ) {
        $cache = $this->prophesize(StorageInterface::class);
        $methodProphecy = new MethodProphecy($cache, $method, $arguments);
        $methodProphecy->willReturn($returnValue);
        $cache->addMethodProphecy($methodProphecy);

        $service = new CacheService($cache->reveal());

        $result = call_user_func_array([$service, $method], $params);

        $this->assertEquals(
            $returnValue,
            $result,
            'Return value ' . json_encode($result) . ' does not match expected ' . json_encode($returnValue)
        );
    }

    /**
     * @test
     */
    public function itReturnsFluentClassForSetOptions()
    {
        $cache = $this->prophesize(StorageInterface::class);
        $cache->setOptions(Argument::type('array'))
            ->willReturn(null);

        $service = new CacheService($cache->reveal());
        $return = $service->setOptions([]);

        $this->assertEquals(
            $service,
            $return
        );
    }

    /**
     * @return array
     */
    public function getMethodDataProvider(): array
    {
        return [
            [
                'getOptions',
                [ Argument::any() ],
                [  ],
                [ true ],
            ],
            [
                'getItem',
                [ Argument::exact('itemKey'), Argument::exact(null), Argument::exact(null) ],
                [ 'itemKey' ],
                'cachedItem',
            ],
            [
                'getItems',
                [ Argument::type('array') ],
                [ ['itemKey'] ],
                [ 'cachedItem' ],
            ],
            [
                'hasItem',
                [ Argument::exact('itemKey') ],
                [ 'itemKey' ],
                true,
            ],
            [
                'hasItems',
                [ Argument::type('array') ],
                [ ['itemKey'] ],
                [ true ],
            ],
            [
                'getMetadata',
                [ Argument::exact('itemKey') ],
                [ 'itemKey' ],
                [ true ],
            ],
            [
                'getMetadatas',
                [ Argument::type('array') ],
                [ ['itemKey'] ],
                [ true ],
            ],
            [
                'setItem',
                [ Argument::exact('itemKey'), Argument::exact('itemValue') ],
                [ 'itemKey', 'itemValue' ],
                true,
            ],
            [
                'setItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'addItem',
                [ Argument::exact('itemKey'), Argument::exact('itemValue') ],
                [ 'itemKey', 'itemValue' ],
                true,
            ],
            [
                'addItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'replaceItem',
                [ Argument::exact('itemKey'), Argument::exact('itemValue') ],
                [ 'itemKey', 'itemValue' ],
                true,
            ],
            [
                'replaceItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'checkAndSetItem',
                [ Argument::exact('itemToken'), Argument::exact('itemKey'), Argument::exact('itemValue') ],
                [ 'itemToken', 'itemKey', 'itemValue' ],
                [ true ],
            ],
            [
                'touchItem',
                [ Argument::exact('itemKey') ],
                [ 'itemKey' ],
                true,
            ],
            [
                'touchItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'removeItem',
                [ Argument::exact('itemKey') ],
                [ 'itemKey' ],
                true,
            ],
            [
                'removeItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'incrementItem',
                [ Argument::exact('itemKey'), Argument::exact('itemValue') ],
                [ 'itemKey', 'itemValue' ],
                true,
            ],
            [
                'incrementItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'decrementItem',
                [ Argument::exact('itemKey'), Argument::exact('itemValue') ],
                [ 'itemKey', 'itemValue' ],
                true,
            ],
            [
                'decrementItems',
                [ Argument::type('array') ],
                [ ['itemKey' => 'itemValue'] ],
                [ true ],
            ],
            [
                'getCapabilities',
                [ Argument::any() ],
                [  ],
                [ true ],
            ],
        ];
    }
}
