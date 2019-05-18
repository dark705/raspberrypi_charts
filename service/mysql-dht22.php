<?php
require_once('../php/autoload.php');

use DAO\ConfigIni;
use PDO\MySQL;
use Device\DHT22;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('../config/config.yaml');
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

    // Write to MySQL
    $query = "INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) ";
    $query .= "VALUES (now(), '$data[temperature]', '$data[humidity]');";
    $mysql->request($query);

} else {
    echo "Error" . PHP_EOL;
}

