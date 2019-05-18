<?php
require_once('../php/autoload.php');

use DAO\ConfigIni;
use PDO\MySQL;
use Device\Serial;
use Device\Peacefair;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('../config/config.yaml');
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

    // Write to MySQL
    $query = "INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) ";
    $query .= "VALUES (now(), '$data[voltage]', '$data[current]', '$data[active]', '$data[energy]');";
    $mysql->request($query);
} else {
    echo "Error" . PHP_EOL;
}

