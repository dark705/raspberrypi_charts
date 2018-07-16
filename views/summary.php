<?php
$urlCommon = 'http://' . $_SERVER['SERVER_ADDR'] . pathinfo($_SERVER['PHP_SELF'])["dirname"] . '/json/';

$config = new mConfigIni('config/config.web.ini');

$last_electro = (new mySensorPzem004t($config))->getLast()[0];
$last_weather = (new mySensorDht22($config))->getLast()[0];



//Get ds18b20 names
$urlDs18b20Names = $urlCommon. 'json.php?sensor=ds18b20&names';
$ds12b20Names = json_decode(file_get_contents($urlDs18b20Names));

//For each ds18b20
foreach ($ds12b20Names as  $serial => $name){
	$urlDs18b20 = $urlCommon . "json.php?sensor=ds18b20&serial=$serial".'&last';
	$ds18b20s[$serial] = json_decode(file_get_contents($urlDs18b20))[0];//get only one, last value, Associate array, key as serial
	$ds18b20s[$serial]->name = $name; //append name property with name of ds18b20 geted from config
}
?>
<div id="last__electro">
	<div class="item">
		<h3>Электросеть:</h3>
		<p id="last__electro__time" class="ontime" class="loading">(показания на: <span><?=$last_electro['datetime']?></span>)</p>
		<p id="last__electro__voltage" class="loading">Напряжение: <span><?=$last_electro['voltage']?></span></p>
		<p id="last__electro__current" class="loading">Ток: <span><?=$last_electro['current']?></span></p>
		<p id="last__electro__active" class="loading">Мощность: <span><?=$last_electro['active']?></span></p>
	</div>
</div>
<div id="last__weather">
	<div class="item">
		<h3>Погода:</h3>
		<p id="last__weather__time" class="ontime" class="loading">(показания на: <span><?=$last_weather['datetime']?></span>)</p>
		<p id="last__weather__temp" class="loading">Температура: <span><?=$last_weather['temperature']?></span></p>
		<p id="last__weather__humidity" class="loading">Влажность: <span><?=$last_weather['humidity']?></span></p>
	</div>
</div>

<?php foreach($ds18b20s as $ds18b20):?>
	<div class="item">
	<h3><?=$ds18b20->name?></h3>
	<p class="ontime">(показания на <?=gmdate("Y-m-d H:i:s", $ds18b20->datetime)?>)</p>
	<p>Температура: <?=$ds18b20->temperature?></p>
	</div>
<?php endforeach;?>
<div class="clear"></div>
<script>
	'use strict';
	/*
	$(function(){
		$.getJSON('json/json.php?sensor=pzem004t&last', function(data){
			var d = new Date((data[0].datetime - 3*60*60) * 1000);
			var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
			$('#last__electro__time span').append(datetime);
			$('#last__electro__voltage').append(data[0].voltage);
			$('#last__electro__current').append(data[0].current);
			$('#last__electro__active').append(data[0].active);
		});
	});
	
	$(function(){
		$.getJSON('json/json.php?sensor=dht22&last', function(data){
			var d = new Date((data[0].datetime - 3*60*60) * 1000);
			var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
			$('#last__weather__time span').append(datetime);
			$('#last__weather__temp').append(data[0].temperature);
			$('#last__weather__humidity').append(data[0].humidity);

		});
	});
	*/
</script>