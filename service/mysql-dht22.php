<?php
require_once('../php/autoload.php');

use DAO\ConfigIni;
use PDO\MySQL;
use Device\DHT22;

$config = new ConfigIni('/var/www/html/chart-ng/config/config.service.ini');
$mysqlConfig = new ConfigIni('/var/www/html/chart-ng/config/config.mysql.service.ini');

$dht22 = new DHT22($config->dhtPath, $config->dhtPin, $config->debug);
$mysql = new MySQL($mysqlConfig);


if ($data = $dht22->getData()) {
    $date = date('Y-m-d H:i:s');

    //for show current values, debug only
    echo 'date:' . $date . PHP_EOL;
    foreach ($data as $key => $val) {
        echo $key . ':' . $val . PHP_EOL;
    }

	// Write to MySQL
	$query = "INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) ";
	$query .="VALUES (now(), '$data[temperature]', '$data[humidity]');";
	$mysql->request($query);

} else {
    echo "Error" . PHP_EOL;
}

