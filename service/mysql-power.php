<?php
spl_autoload_register(function ($class){
	$filename = "/var/www/html/voltage/lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});
$config = new mConfigIni('/var/www/html/voltage/config/config.service.ini');
$peacefair = new mPeacefair(new mSerial($config->devPeacefair));
$mysql = new mMySQL($config->dbHost, $config->dbName, $config->dbLogin,$config->dbPass);






if ($data = $peacefair->getData()){
	$date = date('Y-m-d H:i:s');
	
	//for show current values, debug only
	echo 'date:'.$date.PHP_EOL;
	foreach($data as $key => $val){
		echo $key.':'.$val.PHP_EOL;
	}
	// Write to MySQL
	$query = "INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) ";
	$query .="VALUES (now(), '$data[voltage]', '$data[current]', '$data[active]', '$data[energy]');";
	$mysql->request($query);
}
else
	echo "Error".PHP_EOL;



?>