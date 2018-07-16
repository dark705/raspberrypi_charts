<?php
spl_autoload_register(function ($class){
	$filename = "../lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});

spl_autoload_register(function($class){
	$file = str_replace('\\', '/', $class) . '.php';
	if(file_exists($file)){
		require_once($file);
	}
});


if(array_key_exists('sensor', $_GET)){
	$config = new mConfigIni('../config/config.web.ini');
	$dsNames = new mConfigIni('../config/config.sensor_names.ini');
	
	switch($_GET['sensor']){
		case 'pzem004t':
			$sensor = new mySensorPzem004t($config);
			if (array_key_exists('last', $_GET)){
				echo json_encode($sensor->getLast());
			} else {
				echo json_encode($sensor->get());
			}
			break;

		case 'dht22':
			$sensor = new mySensorDht22($config);
			if (array_key_exists('last', $_GET)){
				echo json_encode($sensor->getLast());
			} else {
				echo json_encode($sensor->get());
			}
			break;

		case 'ds18b20':
			$sensor = new mySensorDs18b20($config, $dsNames);
			if (array_key_exists('last', $_GET) and array_key_exists('serial', $_GET)){
				echo json_encode($sensor->getLast($_GET['serial']));
			}  
			
			if (!array_key_exists('last', $_GET) and array_key_exists('serial', $_GET)){
				echo json_encode($sensor->get($_GET['serial']));
			}
			
			if (array_key_exists('names', $_GET)){
				echo json_encode($sensor->getNames());
			}
			break;
		default:
			echo 'invalid sensor name';
			break;
	}
} else {
	echo 'please choose sensor name, by GET request';
}



?>