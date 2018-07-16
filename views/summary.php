<?php
$config = new mConfigIni('config/config.web.ini');
$dsNames = new mConfigIni('config/config.sensor_names.ini'); 

$lastE = (new mySensorPzem004t($config))->getLast()[0];
$lastW = (new mySensorDht22($config))->getLast()[0];

$ds18b20 = new mySensorDs18b20($config, $dsNames);
?>

<!-- start electro last section -->
<div id="last__electro" class="item">
	<h3>Электросеть:</h3>
	<p id="last__electro__time" class="ontime" >(показания на: <span><?=gmdate("Y-m-d H:i:s", $lastE['datetime'])?></span>)</p>
	<p id="last__electro__voltage">Напряжение: <span><?=$lastE['voltage']?></span></p>
	<p id="last__electro__current">Ток: <span><?=$lastE['current']?></span></p>
	<p id="last__electro__active">Мощность: <span><?=$lastE['active']?></span></p>

</div>
<!-- end -->

<!-- start weather last section -->
<div id="last__weather" class="item ">
	<h3>Погода:</h3>
	<p id="last__weather__time" class="ontime">(показания на: <span><?=gmdate("Y-m-d H:i:s", $lastW['datetime'])?></span>)</p>
	<p id="last__weather__temp">Температура: <span><?=$lastW['temperature']?></span></p>
	<p id="last__weather__humidity">Влажность: <span><?=$lastW['humidity']?></span></p>
</div>
<!-- end -->

<!-- start ds18b20 last section -->
<?php foreach($ds18b20->getNames() as  $serial => $name):?>
<div id="<?=$serial?>" class="item last__ds18b20">
	<h3><?=$name?></h3>
	<?php $lastD = $ds18b20->getLast($serial)[0]; ?>
	<p class="last__ds18b20__time ontime">(показания на: <span><?=gmdate("Y-m-d H:i:s", $lastD['datetime'])?></span>)</p>
	<p class="last__ds18b20__temp">Температура: <span><?=$lastD['temperature']?></span></p>
</div>
<?php endforeach;?>
<!-- end -->


<div class="clear"></div>

<script>
	'use strict';
	function updateLastElectro(){
		$.getJSON('json/json.php?sensor=pzem004t&last', function(data){
			data = data[0];
			var d = new Date((data.datetime - 3*60*60) * 1000);
			data.datetime = d.toString('yyyy-MM-dd HH:mm:ss');			
			$('#last__electro__time span').text(data.datetime);
			$('#last__electro__voltage span').text(data.voltage);
			$('#last__electro__current span').text(data.current);
			$('#last__electro__active span').text(data.active);
		});
	}
	
	function updateLastWeather(){
		$.getJSON('json/json.php?sensor=dht22&last', function(data){
			data = data[0];
			var d = new Date((data.datetime - 3*60*60) * 1000);
			data.datetime = d.toString('yyyy-MM-dd HH:mm:ss');
			$('#last__weather__time span').text(data.datetime);
			$('#last__weather__temp span').text(data.temperature);
			$('#last__weather__humidity span').text(data.humidity);
		});
	}
	
	function updateLastDs18b20(){
		$(".last__ds18b20").each(function(index, thisEl){
			var serial = $(this).attr('id');
			$.getJSON('json/json.php?sensor=ds18b20&serial=' + serial +'&last', function(data){
				data = data[0];
				var d = new Date((data.datetime - 3*60*60) * 1000);
				data.datetime = d.toString('yyyy-MM-dd HH:mm:ss');
				$(thisEl).find('.last__ds18b20__time span').text(data.datetime);
				$(thisEl).find('.last__ds18b20__temp span').text(data.temperature);
			});
		});
	}
	
	$(function(){
		setInterval(function(){
			updateLastElectro();
			updateLastWeather();
			updateLastDs18b20()
		}, 4*60*1000);
	});
</script>