<?php
namespace DAO;

use PDO\MySQL;

class SensorsFactory{
	public static function create($sensor){
		$mysqlConfig = new ConfigIni(__DIR__ . "/../../config/config.mysql.web.ini");
		$namesConfig = new ConfigIni(__DIR__ . "/../../config/config.names.ini"); 
		$mySQL = new MySQL($mysqlConfig);
		switch ($sensor){
			case 'pzem004t':
				return new SensorPzem004t($mySQL, $namesConfig);
			case 'ds18b20':
				return new SensorDs18b20($mySQL, $namesConfig);
			case 'dht22':
				return new SensorDht22($mySQL, $namesConfig);
			default:
				throw new \Exception ('Unknown sensor class name');
		}
	}
}
