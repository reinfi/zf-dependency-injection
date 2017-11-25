<?php

namespace Reinfi\DependencyInjection\Integration\Command;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Command\ExpressiveCacheWarmupCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Reinfi\DependencyInjection\Integration\Command
 */
class ExpressiveWarmupCommandTest extends TestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        class_exists(Inject::class);
        class_exists(InjectConfig::class);
    }

    /**
     * @test
     */
    public function itWarmsupCacheEntries()
    {
        $config = __DIR__ . '/../../resources/expressive_config.php';

        $input = new ArgvInput(
            [
                'command' => 'reinfi:di:cache',
                'config'  => $config,
            ]
        );
        $output = $this->prophesize(OutputInterface::class);

        $command = new ExpressiveCacheWarmupCommand();
        $command->run($input, $output->reveal());
    }

    /**
     * @test
     */
    public function itThrowsExceptionIfPathNotValid()
    {
        $this->expectException(\InvalidArgumentException::class);

        $config = __DIR__ . '/../resources/expressive_config.php';

        $input = new ArgvInput(
            [
                'command' => 'reinfi:di:cache',
                'config'  => $config,
            ]
        );
        $output = $this->prophesize(OutputInterface::class);

        $command = new ExpressiveCacheWarmupCommand();
        $command->run($input, $output->reveal());
    }
}