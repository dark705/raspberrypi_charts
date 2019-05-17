<?php
require_once('../php/autoload.php');

use DAO\ConfigIni;
use PDO\MySQL;
use Device\Serial;
use Device\Peacefair;

$config = new ConfigIni('/var/www/html/chart-ng/config/config.service.ini');
$mysqlConfig = new ConfigIni('/var/www/html/chart/config/config.mysql.service.ini');

$peacefair = new Peacefair(new Serial($config->devPeacefair));
$mysql = new MySQL($mysqlConfig);

if ($data = $peacefair->getData()) {
    $date = date('Y-m-d H:i:s');

    //for show current values, debug only
    echo 'date:' . $date . PHP_EOL;
    foreach ($data as $key => $val) {
        echo $key . ':' . $val . PHP_EOL;
    }

    // Write to MySQL
	$query = "INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) ";
	$query .="VALUES (now(), '$data[voltage]', '$data[current]', '$data[active]', '$data[energy]');";
	$mysql->request($query);
} else {
    echo "Error" . PHP_EOL;
}

