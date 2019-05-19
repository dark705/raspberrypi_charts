<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use Device\Serial;
use Device\Peacefair;
use RMQ\SimpleProducer;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$mysql = new MySQL($config['cli']['db']);
$peacefair = new Peacefair(new Serial($config['cli']['device']['peacefair']['uart']), $config['cli']['debug']);

if ($data = $peacefair->getData()) {
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
        $query = "INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) ";
        $query .= "VALUES (now(), '$data[voltage]', '$data[current]', '$data[active]', '$data[energy]');";
        $mysql->request($query);
    }

    // Publish message to RabbitMQ
    if ($config['cli']['use_rabbitmq']) {
        $producer = new SimpleProducer($config['cli']['rabbitmq']);
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

