<?php
$urlCommon = 'http://' . $_SERVER['SERVER_ADDR'] . pathinfo($_SERVER['PHP_SELF'])["dirname"] . '/json/';

$urlPzem004t = $urlCommon . 'json-pzem004t.php?last';
$urlDht22 = $urlCommon . 'json-dht22.php?last';
$pzem004t = json_decode(file_get_contents($urlPzem004t))[0]; //get only one, last value
$dht22 = json_decode(file_get_contents($urlDht22))[0];


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
<div id="electro__last">
	<div class="item">
		<h3>Электросеть:</h3>
		<p id="electro__last__time" class="ontime">(показания на <?=gmdate("Y-m-d H:i:s", $pzem004t->datetime)?>)</p>
		<p id="electro__last__voltage">Напряжение: <?=$pzem004t->voltage?></p>
		<p id="electro__last__current">Ток: <?=$pzem004t->current?></p>
		<p id="electro__last__active">Мощность: <?=$pzem004t->active?></p>
	</div>
</div>
<div class="item">
<h3>Погода:</h3>
<p class="ontime">(показания на <?=gmdate("Y-m-d H:i:s", $dht22->datetime)?>)</p>
<p>Температура: <?=$dht22->temperature?></p>
<p>Влажность: <?=$dht22->humidity?></p>
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
			console.log(data);
			$('#electro__last__voltage').append(data[0].voltage);
			$('#electro__last__current').append(data[0].current);
			$('#electro__last__active').append(data[0].active);
		});
	});

</script>