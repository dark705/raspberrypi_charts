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

if (isset($_GET['last'])){//if get last show only last value
	$result = $mysql->request("SELECT `datetime`, `temperature`, `humidity` FROM `t_dht22` ORDER BY `datetime` DESC LIMIT 0,1;");
}
else{
	$result = $mysql->request("SELECT `datetime`, `temperature`, `humidity` FROM `t_dht22`;");
}

while ($record = $result->fetch_row()){
	$all[] =  array('datetime' => strtotime($record[0]), 'temperature' => (float)$record[1], 'humidity' => (float)$record[2]);
}

echo json_encode($all);

?>