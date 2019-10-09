<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use RMQ\SensorConsumer;
use Service\Output;
use Symfony\Component\Yaml\Yaml;

$config   = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$output   = Output::getInstance($config['service_rmq']['output'], 'WatcherRMQ');
$consumer = new SensorConsumer($config['service_rmq']['rabbitmq'], $output);
$mysql    = new MySQL($config['service_rmq']['db']);

$consumer->setPDO($mysql);
$consumer->listen();
