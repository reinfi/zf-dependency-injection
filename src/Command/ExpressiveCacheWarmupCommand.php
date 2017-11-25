<?php

namespace Reinfi\DependencyInjection\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

/**
 * @package Reinfi\DependencyInjection\Command
 */
class ExpressiveCacheWarmupCommand extends AbstractWarmupCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->addArgument(
                'config',
                InputArgument::REQUIRED,
                'Path to config which includes services'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start up application with supplied config...');

        $config = $input->getArgument('config');

        $container = new ServiceManager();
        (new Config($config['dependencies']))->configureServiceManager($container);
        $config = $input->getArgument('config');

        $path = stream_resolve_include_path($config);
        if (!is_readable($path)) {
            throw new \InvalidArgumentException("Invalid loader path: {$config}");
        }

        $config = include $path;

        $container = new ServiceManager();
        (new Config($config['dependencies']))->configureServiceManager($container);

        // Inject config
        $container->setService('config', $config);

        $dependenciesConfig = $container->get('config')['dependencies'];

        $this->warmupConfig($container, $dependenciesConfig);

        $output->writeln('Finished cache warmup');
    }
}
