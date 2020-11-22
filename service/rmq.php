<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PDO\MySQL;
use RMQ\SensorConsumer;
use Service\Output;
use Symfony\Component\Yaml\Yaml;

$config   = Yaml::parseFile(__DIR__ . '/../config/config.rmq.db.yaml');
$output   = Output::getInstance($config['output'], 'WatcherRMQ');
$consumer = new SensorConsumer($config['rabbitmq'], $output);
$mysql    = new MySQL($config['db']);

$consumer->setPDO($mysql);
$consumer->listen();
