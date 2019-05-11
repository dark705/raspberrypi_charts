<?php
spl_autoload_register(function ($class){
	$filename = "../lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});

if ($_POST) {
	
	$validSensors = array('pzem004t', 'dht22', 'ds18b20');
	
	if(!array_key_exists('sensor', $_POST))
		exit ('please choose sensor name, by POST request');
	
	if (!in_array($_POST['sensor'], $validSensors))
			exit('invalid sensor name');
	
		
	$sensor = SensorsFactory::create($_POST['sensor']);
	
	if (array_key_exists('names', $_POST)){
		echo json_encode($sensor->getNames());
		exit();
	}
	
	if (array_key_exists('serial', $_POST))
		$serial = $_POST['serial'];
	else
		$serial = null;
	
	if (array_key_exists('last', $_POST)){
		echo json_encode($sensor->getLast($serial));
	} else {
		echo json_encode($sensor->get($serial));
	}
	
	exit('invalid POST');
} else {
	$sensor_pzem004t = SensorsFactory::create('Pzem004t');
	$sensor_dht22 = SensorsFactory::create('Dht22');
	$sensor_ds18b20 = SensorsFactory::create('Ds18b20');
	
	$html_last = new mTemplate('../lib/template/last.php', array('pzem004t' => $sensor_pzem004t, 'dht22' => $sensor_dht22, 'ds18b20' => $sensor_ds18b20));
	$html_pzem004t = new mTemplate('../lib/template/pzem004t.php');
	$html_dht22 = new mTemplate('../lib/template/dht22.php');
	$html_ds18b20 = new mTemplate('../lib/template/ds18b20.php', array('ds18b20' => $sensor_ds18b20));
	
	$html_common = new mTemplate('../lib/template/common.php', array('last' => $html_last->get(), 'pzem004t' => $html_pzem004t->get(), 'dht22' => $html_dht22->get(), 'ds18b20' => $html_ds18b20->get()));
	
	$html_common->show();
}

?>


