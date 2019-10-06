<?php
require_once __DIR__ . '/../php/autoload.php';

use PDO\MySQL;
use Device\Serial;
use Device\Peacefair;
use RMQ\SimpleProducer;
use Service\Output;
use Symfony\Component\Yaml\Yaml;

$config    = Yaml::parseFile(__DIR__ . '/../config/config.yaml');
$output    = Output::getInstance($config['service_sensor']['output'], 'WatcherPower');
$peacefair = new Peacefair(new Serial($config['service_sensor']['device']['peacefair']['uart']), $output);

$output->info('Check power');
if ($data = $peacefair->getData()) {
    $output->info('Data', $data);

    // Direct write to MySQL
    if ($config['service_sensor']['direct_db_write']) {
        $mysql = new MySQL($config['service_sensor']['db']);
        $query = "INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) ";
        $query .= "VALUES (now(), '$data[voltage]', '$data[current]', '$data[active]', '$data[energy]');";
        $mysql->request($query);
    }

    // Publish message to RabbitMQ
    if ($config['service_sensor']['use_rabbitmq']) {
        $producer = new SimpleProducer($config['service_sensor']['rabbitmq'], $output);
        $message  = [
            'sensor' => 'peacefair',
            'date'   => date('Y-m-d H:i:s'),
            'data'   => $data
        ];
        $producer->publish(json_encode($message));
    }

} else {
    $output->error('Fail to get data from sensor');
}

