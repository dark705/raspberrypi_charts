<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use Device\DHT22;
use RMQ\SimpleProducer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$mysql = new MySQL($config['cli']['db']);
$dht22 = new DHT22($config['cli']['device']['dht'], $config['cli']['debug']);

if ($data = $dht22->getData()) {
    $date = date('Y-m-d H:i:s');

    //Show info in console
    if ($config['cli']['stdout']) {
        echo 'date:' . $date . PHP_EOL;
        foreach ($data as $key => $val) {
            echo $key . ':' . $val . PHP_EOL;
        }
    }

    // Direct write to MySQL
    if ($config['cli']['direct_db_write']) {
        $query = "INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) ";
        $query .= "VALUES (now(), '$data[temperature]', '$data[humidity]');";
        $mysql->request($query);
    }

    // Publish message to RabbitMQ
    if ($config['cli']['use_rabbitmq']) {
        $producer = new SimpleProducer($config['cli']['rabbitmq']);
        $message = [
            'sensor' => 'dht22',
            'date' => $date,
            'data' => $data
        ];
        $producer->publish(json_encode($message));
    }
} else {
    echo "Error" . PHP_EOL;
}

