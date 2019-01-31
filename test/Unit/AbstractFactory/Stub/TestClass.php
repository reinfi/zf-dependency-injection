<?php
declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\AbstractFactory\Stub;

/**
 * Class TestClass
 *
 * @package Reinfi\DependencyInjection\Unit\AbstractFactory\Stub
 */
class TestClass
{

    /**
     * @var TestService
     */
    public $testService;

    /**
     * @var string
     */
    public $foo;

    /**
     * @var string
     */
    public $untyped;

    /**
     * The test_class constructor.
     *
     * @param TestService $testService
     * @param string $foo
     * @param string $untyped
     */
    public function __construct(TestService $testService, string $foo, $untyped = '')
    {
        $this->testService = $testService;
        $this->foo = $foo;
        $this->untyped = $untyped;
    }

}