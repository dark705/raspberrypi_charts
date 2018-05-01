<?php
spl_autoload_register(function ($class){
	$filename = "/var/www/html/voltage/lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});
$config = new mConfigIni('/var/www/html/voltage/config/config.service.ini');
$ds = new mDS18B20();
$mysql = new mMySQL($config->dbHost, $config->dbName, $config->dbLogin,$config->dbPass);


if ($data = $ds->getAll()){
	$date = date('Y-m-d H:i:s');
	
	//for show current values, debug only
	echo 'date:'.$date.PHP_EOL;
	foreach($data as $key => $val){
		echo $key.' = '.$val.PHP_EOL;
	}
	foreach($data as $key => $val){
		$query = "INSERT INTO `ds18b20` (`datetime`, `serial`, `temperature`) ";
		$query .="VALUES (now(), '$key', '$val');";
		$mysql->request($query);
	}
	

}
else{
	echo "Error".PHP_EOL;
	var_dump($data);
}
?>