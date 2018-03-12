<?php
$urlCommon = 'http://' . $_SERVER['SERVER_ADDR'] . pathinfo($_SERVER['PHP_SELF'])["dirname"] . '/json/';

$urlPzem004t = $urlCommon . 'json-pzem004t.php?last';
$urlDht22 = $urlCommon . 'json-dht22.php?last';
$pzem004t = json_decode(file_get_contents($urlPzem004t))[0]; //get only one, last value
$dht22 = json_decode(file_get_contents($urlDht22))[0];

//Get ds18b20 names
$urlSensorsNames = $urlCommon. 'json-ds18b20.php?names';
$sensorsNames = json_decode(file_get_contents($urlSensorsNames));

//For each ds18b20
foreach ($sensorsNames as  $serial => $name){
	$urlSensor = $urlCommon . "json-ds18b20.php?serial=$serial".'&last';
	$sensor = json_decode(file_get_contents($urlSensor))[0];//get only one, last value
	$temperature = $sensor[1];
	$time = $sensor[0];
	$sensors[$serial] = array('time'=> $time, 'name' => $name, 'temperature' => $temperature);//Associate array, key as serial
}

$time_electro =  $pzem004t[0];
$voltage = $pzem004t[1];
$current = $pzem004t[2];
$power = $pzem004t[3];

$time_wheather =  $dht22[0];
$temperature = $dht22[1];
$humidity = $dht22[2];
?>

<div class="item">
<h3>Электросеть:</h3>
<p class="ontime">(показания на <?=gmdate("Y-m-d H:i:s", $time_electro)?>)</p>
<p>Напряжение: <?=$voltage?></p>
<p>Ток: <?=$current?></p>
<p>Мощность: <?=$power?></p>
</div>

<div class="item">
<h3>Погода:</h3>
<p class="ontime">(показания на <?=gmdate("Y-m-d H:i:s", $time_wheather)?>)</p>
<p>Температура: <?=$temperature?></p>
<p>Влажность: <?=$humidity?></p>
</div>

<?foreach($sensors as $sensor):?>
	<div class="item">
	<h3><?=$sensor['name']?></h3>
	<p class="ontime">(показания на <?=gmdate("Y-m-d H:i:s", $sensor['time'])?>)</p>
	<p>Температура: <?=$sensor['temperature']?></p>
	</div>
<?endforeach;?>
<div class="clear"></div>