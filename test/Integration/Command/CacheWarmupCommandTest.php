<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Integration\Command;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Annotation\InjectConstant;
use Reinfi\DependencyInjection\Command\CacheWarmupCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Integration\Command
 */
#[Group('integration')]
final class CacheWarmupCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        class_exists(Inject::class);
        class_exists(InjectConfig::class);
        class_exists(InjectConstant::class);
    }

    public function testItWarmsupCacheEntries(): void
    {
        $config = __DIR__ . '/../../resources/application_config.php';

        $argvInput = new ArgvInput(
            [
                'command' => 'reinfi:di:cache',
                'applicationConfig' => $config,
            ]
        );
        $output = $this->createMock(OutputInterface::class);
        $output->expects($this->atLeastOnce())
            ->method('writeln')
            ->with($this->isString());

        $cacheWarmupCommand = new CacheWarmupCommand();
        $cacheWarmupCommand->run($argvInput, $output);
    }

    public function testItThrowsExceptionIfPathNotValid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $config = __DIR__ . '/../resources/application_config.php';

        $argvInput = new ArgvInput(
            [
                'command' => 'reinfi:di:cache',
                'applicationConfig' => $config,
            ]
        );
        $output = $this->createMock(OutputInterface::class);

        $cacheWarmupCommand = new CacheWarmupCommand();
        $cacheWarmupCommand->run($argvInput, $output);
    }
}
