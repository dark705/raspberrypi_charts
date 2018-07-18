<?php
spl_autoload_register(function ($class){
	$filename = "/var/www/html/chart/lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});
$config = new mConfigIni('/var/www/html/chart/config/config.service.ini');
$my = new mConfigIni('/var/www/html/chart/config/config.mysql.service.ini');

$dht22 = new mDHT22($config->dhtPath, $config->dhtPin, $config->debug);
$mysql = new mMySQL($my);


if ($data = $dht22->getData()){
	$date = date('Y-m-d H:i:s');
	
	//for show current values, debug only
	echo 'date:'.$date.PHP_EOL;
	foreach($data as $key => $val){
		echo $key.':'.$val.PHP_EOL;
	}
	
	// Write to MySQL
	$query = "INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) ";
	$query .="VALUES (now(), '$data[temperature]', '$data[humidity]');";
	$mysql->request($query);
	
}
else
	echo "Error".PHP_EOL;



?>