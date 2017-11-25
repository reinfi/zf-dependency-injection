<?php

namespace Reinfi\DependencyInjection\Integration\Command;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Command\CacheWarmupCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Reinfi\DependencyInjection\Integration\Command
 */
class CacheWarmupCommandTest extends TestCase
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
        $config = __DIR__ . '/../../resources/application_config.php';

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