<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use RMQ\SensorConsumer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$consumer = new SensorConsumer($config['cli']['rabbitmq'], $config['cli']['debug']);
$mysql = new MySQL($config['cli']['db']);
$consumer->setPDO($mysql);
$consumer->listen();
