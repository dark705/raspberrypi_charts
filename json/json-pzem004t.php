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
	$result = $mysql->request("SELECT `datetime`, `voltage`, `current`,`active` FROM `pzem004t` ORDER BY `datetime` DESC LIMIT 0,1;");
}
else{
	$result = $mysql->request("SELECT `datetime`, `voltage`, `current`,`active` FROM `pzem004t` WHERE `datetime` > NOW() - INTERVAL 31 DAY;");
}

while ($record = $result->fetch_row()){
	$all[] =  array('datetime' => strtotime($record[0]), 'voltage' => (float)$record[1], 'current' => (float)$record[2], 'active' => (float)$record[3]);
}

echo json_encode($all);


?>