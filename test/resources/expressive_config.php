<?php

use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;

$config = require 'config.php';
$config['dependencies'] = $config['service_manager'];

$aggregator = new ConfigAggregator(
    [
        \Reinfi\DependencyInjection\ConfigProvider::class,
        new ArrayProvider($config),
    ]
);

return $aggregator->getMergedConfig();