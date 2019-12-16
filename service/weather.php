<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PDO\MySQL;
use Device\DHT22;
use RMQ\SimpleProducer;
use Service\Output;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$output = Output::getInstance($config['service_sensor']['output'], 'WatcherWeather');
$dht22  = new DHT22($config['service_sensor']['device']['dht'], $output);

$output->info('Check weather');
if ($data = $dht22->getData()) {
    $output->info('Data', $data);

    // Direct write to MySQL
    if ($config['service_sensor']['direct_db_write']) {
        $mysql = new MySQL($config['service_sensor']['db']);
        $query = "INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) ";
        $query .= "VALUES (now(), '$data[temperature]', '$data[humidity]');";
        $mysql->request($query);
    }

    // Publish message to RabbitMQ
    if ($config['service_sensor']['use_rabbitmq']) {
        $producer = new SimpleProducer($config['service_sensor']['rabbitmq'], $output);
        $message  = [
            'sensor' => 'dht22',
            'date'   => date('Y-m-d H:i:s'),
            'data'   => $data
        ];
        $producer->publish(json_encode($message));
    }
} else {
    $output->error('Fail to get data from sensor');
}

