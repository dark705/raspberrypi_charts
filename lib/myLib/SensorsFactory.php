<?
class SensorsFactory{
	public static function create($sensor, $config, $config2){
		$sensor = 'mySensor'.ucfirst($sensor);
		return new $sensor($config, $config2);
	}
}

?>