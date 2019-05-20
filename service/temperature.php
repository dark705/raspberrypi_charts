<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use Device\DS18B20;
use RMQ\SimpleProducer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$ds = new DS18B20($config['service_sensor']['debug']);

if ($data = $ds->getAll()) {
    $date = date('Y-m-d H:i:s');

    //Show info in console
    if ($config['service_sensor']['stdout']) {
        echo 'date:' . $date . PHP_EOL;
        foreach ($data as $serial => $value) {
            echo $serial . ' = ' . $value . PHP_EOL;
        }
    }

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
        $producer = new SimpleProducer($config['service_sensor']['rabbitmq']);
        foreach ($data as $serial => $value) {
            $message = [
                'sensor' => 'ds18b20',
                'date' => $date,
                'data' => [
                    'serial' => $serial,
                    'temperature' => $value
                ]
            ];
            $producer->publish(json_encode($message));
        }
    }
} else {
    echo "Error" . PHP_EOL;
}