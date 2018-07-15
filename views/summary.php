<?php
$urlCommon = 'http://' . $_SERVER['SERVER_ADDR'] . pathinfo($_SERVER['PHP_SELF'])["dirname"] . '/json/';



//Get ds18b20 names
$urlDs18b20Names = $urlCommon. 'json-ds18b20.php?names';
$ds12b20Names = json_decode(file_get_contents($urlDs18b20Names));

//For each ds18b20
foreach ($ds12b20Names as  $serial => $name){
	$urlDs18b20 = $urlCommon . "json-ds18b20.php?serial=$serial".'&last';
	$ds18b20s[$serial] = json_decode(file_get_contents($urlDs18b20))[0];//get only one, last value, Associate array, key as serial
	$ds18b20s[$serial]->name = $name; //append name property with name of ds18b20 geted from config
}
?>
<div id="last__electro">
	<div class="item">
		<h3>Электросеть:</h3>
		<p id="last__electro__time" class="ontime" class="loading">(показания на: <span></span>)</p>
		<p id="last__electro__voltage" class="loading">Напряжение: </p>
		<p id="last__electro__current" class="loading">Ток: </p>
		<p id="last__electro__active" class="loading">Мощность: </p>
	</div>
</div>
<div id="last__weather">
	<div class="item">
		<h3>Погода:</h3>
		<p id="last__weather__time" class="ontime" class="loading">(показания на: <span></span>)</p>
		<p id="last__weather__temp" class="loading">Температура: </p>
		<p id="last__weather__humidity" class="loading">Влажность: </p>
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
	$(function(){
		$.getJSON('json/json-pzem004t.php?last', function(data){
			var d = new Date((data[0].datetime - 3*60*60) * 1000);
			var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
			$('#last__electro__time span').append(datetime);
			$('#last__electro__voltage').append(data[0].voltage);
			$('#last__electro__current').append(data[0].current);
			$('#last__electro__active').append(data[0].active);
		});
	});
	
	$(function(){
		$.getJSON('json/json-dht22.php?last', function(data){
			var d = new Date((data[0].datetime - 3*60*60) * 1000);
			var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
			$('#last__weather__time span').append(datetime);
			$('#last__weather__temp').append(data[0].temperature);
			$('#last__weather__humidity').append(data[0].humidity);

		});
	});
	
</script>