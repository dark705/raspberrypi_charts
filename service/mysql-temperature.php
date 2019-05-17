<?php
require_once('../php/autoload.php');

use DAO\ConfigIni;
use PDO\MySQL;
use Device\DS18B20;

$mysqlConfig = new ConfigIni('/var/www/html/chart-ng/config/config.mysql.service.ini');
$mysql = new MySQL($mysqlConfig);

$ds = new DS18B20();

if ($data = $ds->getAll()) {
    $date = date('Y-m-d H:i:s');

    //for show current values, debug only
    echo 'date:' . $date . PHP_EOL;
    foreach ($data as $key => $val) {
        echo $key . ' = ' . $val . PHP_EOL;
    }
    foreach ($data as $key => $val) {
        $query = "INSERT INTO `ds18b20` (`datetime`, `serial`, `temperature`) ";
        $query .= "VALUES (now(), '$key', '$val');";
        $mysql->request($query);
    }
} else {
    echo "Error" . PHP_EOL;
    var_dump($data);
}