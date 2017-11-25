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

        $dependenciesConfig = $container->get('config')['dependencies'];

        $this->warmupConfig($container, $dependenciesConfig);

        $output->writeln('Finished cache warmup');
    }
}
