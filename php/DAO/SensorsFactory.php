<?php
namespace DAO;

use PDO\MySQL;

class SensorsFactory{
	public static function create($sensor, $config){
		$mySQL = new MySQL($config['db']);

		switch ($sensor){
			case 'pzem004t':
				return new SensorPzem004t($mySQL);
			case 'ds18b20':
				return new SensorDs18b20($mySQL, $config['names']);
			case 'dht22':
				return new SensorDht22($mySQL);
            case 'bmp280':
                return new SensorBmp280($mySQL);
            case 'ups':
                return new SensorUPS($mySQL);
			default:
				throw new \Exception ('Unknown sensor class name');
		}
	}
}
