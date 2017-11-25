<?php

namespace Reinfi\DependencyInjection\Command;

use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Mvc\Application;

class CacheWarmupCommand extends Command
{
    use CacheKeyTrait;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('reinfi:di cache warmup')
            ->addArgument(
                'applicationConfig',
                InputArgument::REQUIRED,
                'Path to application config which includes services'
            )
            ->setDescription('Warm up the cache');
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

        $extractor = $container->get(ExtractorInterface::class);
        $resolverService = $container->get(ResolverService::class);
        $cache = $container->get(CacheService::class);

        $factoriesConfig = $serviceManagerConfig['factories'];

        foreach ($factoriesConfig as $className => $factoryClass) {
            if ($factoryClass === InjectionFactory::class) {
                $injections = array_merge(
                    $extractor->getPropertiesInjections($className),
                    $extractor->getConstructorInjections($className)
                );

                $cache->setItem(
                    $this->buildCacheKey($className),
                    $injections
                );

                continue;
            }

            if ($factoryClass === AutoWiringFactory::class) {
                $injections = $resolverService->resolve($className);

                $cache->setItem(
                    $this->buildCacheKey($className),
                    $injections
                );

                continue;
            }
        }

        $output->writeln('Finished cache warmup');
    }

}
