<?php
declare(strict_types=1);

namespace Reinfi\DependencyInjection\Unit\AbstractFactory;

use ArgumentCountError;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\AbstractFactory\FallbackAutowireFactory;
use Reinfi\DependencyInjection\Unit\AbstractFactory\Stub\TestService;
use Reinfi\DependencyInjection\Unit\AbstractFactory\Stub\TestClass;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class FallbackAutowireFactoryTest
 *
 * @package Reinfi\DependencyInjection\Unit\AbstractFactory
 */
class FallbackAutowireFactoryTest extends TestCase
{

    /**
     * @var ContainerInterface|MockObject
     */
    private $containerMock;

    /**
     * Set up the tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->containerMock = $this->createMock(ContainerInterface::class);
    }

    /**
     * Test if all dependencies of a class can be resolved
     *
     * @return void
     */
    public function test__invoke(): void
    {
        /** @var TestService|MockObject $testServiceMock */
        $testServiceMock = $this->createMock(TestService::class);
        $foo = 'bar';

        $this->containerMock->expects(static::once())
            ->method('get')
            ->with(TestService::class)
            ->willReturn($testServiceMock);

        $subject = new FallbackAutowireFactory();

        /** @var TestClass $instance */
        $instance = $subject($this->containerMock, TestClass::class, ['foo' => $foo]);

        Assert::assertInstanceOf(TestClass::class, $instance);
        Assert::assertSame($testServiceMock, $instance->testService);
        Assert::assertSame($foo, $instance->foo);
    }

    /**
     * Test if a class cannot be resolved if a service could not be resolved
     *
     * @return void
     */
    public function test__invokeWithMissingDiService(): void
    {
        $expectedException = new ServiceNotFoundException('Service could not be resolved');

        $this->containerMock->expects(static::once())
            ->method('get')
            ->with(TestService::class)
            ->willThrowException($expectedException);

        $this->expectExceptionObject($expectedException);

        $subject = new FallbackAutowireFactory();
        $subject($this->containerMock, TestClass::class, ['foo' => 'bar']);
    }

    /**
     * Test if a class cannot be resolved if an argument is missing from the options array
     *
     * @return void
     */
    public function test__invokeWithMissingOptionArgument(): void
    {
        $this->containerMock->expects(static::once())
            ->method('get')
            ->with(TestService::class)
            ->willReturn($this->createMock(TestService::class));

        $this->expectException(ArgumentCountError::class);

        $subject = new FallbackAutowireFactory();
        $subject($this->containerMock, TestClass::class);
    }

    /**
     * Test if the factory indicates that it can create every existing class
     *
     * @return void
     */
    public function testCanCreate(): void
    {
        $subject = new FallbackAutowireFactory();

        Assert::assertTrue($subject->canCreate($this->containerMock, TestClass::class));
        Assert::assertFalse($subject->canCreate($this->containerMock, 'NotExistingClass'));
        Assert::assertFalse($subject->canCreate($this->containerMock, ContainerInterface::class));
    }

}
