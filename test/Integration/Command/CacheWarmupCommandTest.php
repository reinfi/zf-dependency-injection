<?php

namespace Reinfi\DependencyInjection\Test\Integration\Command;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Annotation\InjectConstant;
use Reinfi\DependencyInjection\Command\CacheWarmupCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Command
 *
 * @group integration
 */
class CacheWarmupCommandTest extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        class_exists(Inject::class);
        class_exists(InjectConfig::class);
        class_exists(InjectConstant::class);
    }

    /**
     * @test
     */
    public function itWarmsupCacheEntries()
    {
        $config = __DIR__ . '/../../resources/application_config.php';

        $input = new ArgvInput(
            [
                'command'           => 'reinfi:di:cache',
                'applicationConfig' => $config,
            ]
        );
        $output = $this->prophesize(OutputInterface::class);
        $output->writeln(Argument::type('string'))->shouldBeCalled();

        $command = new CacheWarmupCommand();
        $command->run($input, $output->reveal());
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfPathNotValid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $config = __DIR__ . '/../resources/application_config.php';

        $input = new ArgvInput(
            [
                'command'           => 'reinfi:di:cache',
                'applicationConfig' => $config,
            ]
        );
        $output = $this->prophesize(OutputInterface::class);

        $command = new CacheWarmupCommand();
        $command->run($input, $output->reveal());
    }
}
