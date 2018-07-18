<?php
class SensorsFactory{
	public static function create($sensor){
		$mysql = new mConfigIni(__DIR__ . "/../../config/config.mysql.web.ini");
		$names = new mConfigIni(__DIR__ . "/../../config/config.names.ini"); 
		$my = new mMySQL($mysql);
		$sensor = 'mySensor'.ucfirst($sensor);
		return new $sensor($my, $names);
	}
}

?>