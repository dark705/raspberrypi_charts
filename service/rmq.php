<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use RMQ\SensorConsumer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$consumer = new SensorConsumer($config['service_rmq']['rabbitmq'], $config['service_rmq']['stdout'], $config['service_rmq']['debug']);
$mysql = new MySQL($config['service_rmq']['db']);
$consumer->setPDO($mysql);
$consumer->setAck($config['service_rmq']['rabbitmq']['ack']);
$consumer->listen();
