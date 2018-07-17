<?php
spl_autoload_register(function ($class){
	$filename = "lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});

$config = new mConfigIni('config/config.web.ini');
$names = new mConfigIni('config/config.names.ini'); 

$sensor_pzem004t = SensorsFactory::create('Pzem004t', $config, null);
$sensor_dht22 = SensorsFactory::create('Dht22', $config, null);
$sensor_ds18b20 = SensorsFactory::create('Ds18b20', $config, $names);

$html_last = new mTemplate('template/last.php', array('pzem004t' => $sensor_pzem004t, 'dht22' => $sensor_dht22, 'ds18b20' => $sensor_ds18b20));
$html_pzem004t = new mTemplate('template/pzem004t.php');
$html_dht22 = new mTemplate('template/dht22.php');
$html_ds18b20 = new mTemplate('template/ds18b20.php', array('ds18b20' => $sensor_ds18b20));

$html_common = new mTemplate('template/common.php', array('last' => $html_last->get(), 'pzem004t' => $html_pzem004t->get(), 'dht22' => $html_dht22->get(), 'ds18b20' => $html_ds18b20->get()));

$html_common->show();
?>


