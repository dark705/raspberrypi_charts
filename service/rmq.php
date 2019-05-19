<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use RMQ\SensorConsumer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$consumer = new SensorConsumer($config['web']['rabbitmq'], $config['web']['stdout'], $config['web']['debug']);
$mysql = new MySQL($config['web']['db']);
$consumer->setPDO($mysql);
$consumer->listen();
