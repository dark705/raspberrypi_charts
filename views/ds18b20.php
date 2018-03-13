<?php
$ds18b20Names = 'http://' . $_SERVER['SERVER_ADDR'] . pathinfo($_SERVER['PHP_SELF'])["dirname"] . '/json/' . 'json-ds18b20.php?names';
$ds18b20s = json_decode(file_get_contents($ds18b20Names));
foreach($ds18b20s as $serial => $name)
{
?>
<div id="ds18b20_<?php echo $serial;?>" style="height: 400px; min-width: 310px"></div>
<script>
			$.getJSON('json/json-ds18b20.php?serial=<?php echo $serial;?>', function (data) {

				var temperature = [],
					dataLength = data.length;
				for (var i = 0; i < dataLength; i++) {
					temperature.push([data[i]["datetime"] * 1000, data[i]["temperature"]]);
				}
				
				
				Highcharts.stockChart('ds18b20_<?php echo $serial;?>', {
					rangeSelector: {
						selected: 3,
						buttons: [{
							type: 'minute',
							count: 10,
							text: '10м'
						}, {
							type: 'hour',
							count: 1,
							text: '1час'
						}, {
							type: 'hour',
							count: 6,
							text: '6час'
						}, {
							type: 'day',
							count: 1,
							text: '1дн'
						}, {
							type: 'week',
							count: 1,
							text: 'неделя'
						}, {
							type: 'month',
							count: 1,
							text: 'мес'
						}, {
							type: 'year',
							count: 1,
							text: 'год'
						}, {
							type: 'all',
							text: 'Всё'
						}]
					},
					
					chart: {
						events: {
						  load: updateLegendLabel
						}
					},
					
					legend: {
						enabled: true,
						floating: false,
						verticalAlign: 'bottom',
						useHTML: true
					},
					
					title: {
						text: '<?php echo $name;?>'
					},
					
					xAxis: {
						type: 'datetime',
						ordinal: false,
						events: {
						  afterSetExtremes: updateLegendLabel
						}
					},
					yAxis: {
						labels: {
							align: 'right',
							x: -3
						},
						title: {
							text: 'Температура'
						}
					},
					series: [{
						name: 'С° <?php echo $name;?>',
						data: temperature,
						type: 'spline',
						tooltip: {
							valueDecimals: 2
						}
					}]
				});
			});
</script>
<?php }?>