<?php
require_once('../php/autoload.php');

use DAO\ConfigIni;
use PDO\MySQL;
use Device\DS18B20;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('../config/config.yaml');
$mysql = new MySQL($config['cli']['db']);
$ds = new DS18B20($config['cli']['debug']);

if ($data = $ds->getAll()) {
    $date = date('Y-m-d H:i:s');

    //Show info in console
    if ($config['cli']['stdout']) {
        echo 'date:' . $date . PHP_EOL;
        foreach ($data as $serial => $temperature) {
            echo $serial . ' = ' . $temperature . PHP_EOL;
        }
    }

    // Write to MySQL
    foreach ($data as $serial => $temperature) {
        $query = "INSERT INTO `ds18b20` (`datetime`, `serial`, `temperature`) ";
        $query .= "VALUES (now(), '$serial', '$temperature');";
        $mysql->request($query);
    }
} else {
    echo "Error" . PHP_EOL;
}