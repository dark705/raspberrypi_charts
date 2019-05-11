<?php
spl_autoload_register(function ($class){
	$filename = "../lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});
$validSensors = array('pzem004t', 'dht22', 'ds18b20');

if(!array_key_exists('sensor', $_GET))
	exit ('please choose sensor name, by GET request');

if (!in_array($_GET['sensor'], $validSensors))
		exit('invalid sensor name');

	
$sensor = SensorsFactory::create($_GET['sensor']);

if (array_key_exists('names', $_GET)){
	echo json_encode($sensor->getNames());
	exit();
}

if (array_key_exists('serial', $_GET))
	$serial = $_GET['serial'];
else
	$serial = null;

if (array_key_exists('last', $_GET)){
	echo json_encode($sensor->getLast($serial));
} else {
	echo json_encode($sensor->get($serial));
}


?>