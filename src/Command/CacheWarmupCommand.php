<?php

namespace Reinfi\DependencyInjection\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Mvc\Application;

/**
 * @package Reinfi\DependencyInjection\Command
 */
class CacheWarmupCommand extends AbstractWarmupCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument(
                'applicationConfig',
                InputArgument::REQUIRED,
                'Path to application config which includes services'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start up application with supplied config...');

        $config = $input->getArgument('applicationConfig');
        $path   = stream_resolve_include_path($config);
        if (!is_readable($path)) {
            throw new \InvalidArgumentException("Invalid loader path: {$config}");
        }

        $container = Application::init(include $path)
            ->getServiceManager();

        $serviceManagerConfig = $container->get('config')['service_manager'];

        $this->warmupConfig($container, $serviceManagerConfig);

        $output->writeln('Finished cache warmup');
    }
}
