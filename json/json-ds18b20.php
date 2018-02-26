<?php
//autoload my lib's
spl_autoload_register(function ($class){
	$filename = "../lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});
date_default_timezone_set( 'UTC' );

$config = new mConfigIni('../config/config.web.ini');
$mysql = new mMySQL($config->dbHost, $config->dbName, $config->dbLogin,$config->dbPass);
$snames = new mConfigIni('../config/config.sensor_names.ini');


//get serials DS18B20 from DB and names from config
$result = $mysql->request("SELECT DISTINCT `serial` FROM `ds18b20`;");
while ($record = $result->fetch_row()){
	if ($snames->$record[0])
		$serials[$record[0]] = $snames->$record[0];
	else
		$serials[$record[0]] = $record[0];
}

//show all serials and names in array
if (isset($_GET['serials'])){
	echo json_encode($serials);
}


if (isset($_GET['serial']) and array_key_exists($_GET['serial'], $serials)){
	$serial = $_GET['serial'];	
	$result = $mysql->request("SELECT `datetime`, `temperature` FROM `ds18b20` WHERE `serial` = '$serial';");
	while ($record = $result->fetch_row()){
		$all[] =  array(strtotime($record[0]), (float)$record[1]);
	}
	echo json_encode($all);
}
?>