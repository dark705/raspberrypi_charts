<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use Device\Serial;
use Device\Peacefair;
use RMQ\SimpleProducer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$peacefair = new Peacefair(new Serial($config['service_sensor']['device']['peacefair']['uart']), $config['service_sensor']['debug']);

if ($data = $peacefair->getData()) {
    $date = date('Y-m-d H:i:s');

    //Show info in console
    if ($config['service_sensor']['stdout']) {
        echo 'date:' . $date . PHP_EOL;
        foreach ($data as $key => $val) {
            echo $key . ':' . $val . PHP_EOL;
        }
    }

    // Direct write to MySQL
    if ($config['service_sensor']['direct_db_write']) {
        $mysql = new MySQL($config['service_sensor']['db']);
        $query = "INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) ";
        $query .= "VALUES (now(), '$data[voltage]', '$data[current]', '$data[active]', '$data[energy]');";
        $mysql->request($query);
    }

    // Publish message to RabbitMQ
    if ($config['service_sensor']['use_rabbitmq']) {
        $producer = new SimpleProducer($config['service_sensor']['rabbitmq']);
        $message = [
            'sensor' => 'peacefair',
            'date' => $date,
            'data' => $data
        ];
        $producer->publish(json_encode($message));
    }

} else {
    echo "Error" . PHP_EOL;
}

