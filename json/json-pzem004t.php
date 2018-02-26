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

$result = $mysql->request("SELECT `datetime`, `voltage`, `current`,`active` FROM `t_power`;");

while ($record = $result->fetch_row()){
	$all[] =  array(strtotime($record[0]), (float)$record[1], (float)$record[2], (float)$record[3]);
}

echo json_encode($all);

?>