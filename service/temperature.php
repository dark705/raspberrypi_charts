<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PDO\MySQL;
use Device\DS18B20;
use RMQ\SimpleProducer;
use Service\Output;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$output = Output::getInstance($config['service_sensor']['output'], 'WatcherTemperature');
$ds     = new DS18B20($output);

$output->info('Check temperatures');
if ($data = $ds->getAll()) {
    $output->info('Data', $data);

    // Direct write to MySQL
    if ($config['service_sensor']['direct_db_write']) {
        $mysql = new MySQL($config['service_sensor']['db']);
        foreach ($data as $serial => $value) {
            $query = "INSERT INTO `ds18b20` (`datetime`, `serial`, `temperature`) ";
            $query .= "VALUES (now(), '$serial', '$value');";
            $mysql->request($query);
        }
    }

    // Publish message to RabbitMQ
    if ($config['service_sensor']['use_rabbitmq']) {
        $producer = new SimpleProducer($config['service_sensor']['rabbitmq'], $output);
        foreach ($data as $serial => $value) {
            $message = [
                'sensor' => 'ds18b20',
                'date'   => date('Y-m-d H:i:s'),
                'data'   => [
                    'serial'      => $serial,
                    'temperature' => $value
                ]
            ];
            $producer->publish(json_encode($message));
        }
    }
} else {
    $output->error('Fail to get data from sensor');
}