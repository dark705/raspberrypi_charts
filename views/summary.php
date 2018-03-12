<?php
$urlCommon = 'http://' . $_SERVER['SERVER_ADDR'] . pathinfo($_SERVER['PHP_SELF'])["dirname"] . '/json/';

$urlElectro = $urlCommon . 'json-pzem004t.php?last';
$urlWeather = $urlCommon . 'json-dht22.php?last';
$electro = json_decode(file_get_contents($urlElectro))[0]; //get only one, last value
$weather = json_decode(file_get_contents($urlWeather))[0];

//Get sensors names
$urlSensorsNames = $urlCommon. 'json-ds18b20.php?names';
$sensorsNames = json_decode(file_get_contents($urlSensorsNames));

//For each sensor
foreach ($sensorsNames as  $serial => $name){
	$urlSensor = $urlCommon . "json-ds18b20.php?serial=$serial".'&last';
	$temperatureSensor = json_decode(file_get_contents($urlSensor))[0][1];//get only one, last value, and only temp
	$sensors[$serial] = array('name' => $name, 'temperature' => $temperatureSensor);//Associate array, key as serial
}

$voltage = $electro[1];
$current = $electro[2];
$power = $electro[3];
$temperature = $weather[1];
$humidity = $weather[2];
?>

<div class="item">
<h3>Электросеть:</h3>
<p>Напряжение: <?=$voltage?></p>
<p>Ток: <?=$current?></p>
<p>Мощность: <?=$power?></p>
</div>

<div class="item">
<h3>Погода:</h3>
<p>Температура: <?=$temperature?></p>
<p>Влажность: <?=$humidity?></p>
</div>

<?foreach($sensors as $sensor):?>
	<div class="item">
	<h3><?=$sensor['name']?></h3>
	<p>Температура: <?=$sensor['temperature']?></p>
	</div>
<?endforeach;?>
<div class="clear"></div>