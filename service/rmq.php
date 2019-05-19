<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use RMQ\SimpleConsumer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$consumer = new SimpleConsumer($config['cli']['rabbitmq'], $config['cli']['stdout']);
$mysql = new MySQL($config['cli']['db']);
$consumer->setPDO($mysql);
$consumer->listen();
