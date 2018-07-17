<?foreach($ds18b20->getNames() as $serial => $name):?>
<div class="chart">
	<a name="chart__<?=$serial?>"></a>
	<div id="ds18b20_<?=$serial?>" style="height: 400px; min-width: 310px"></div>
</div>
<script>
			$.getJSON('json/json.php?sensor=ds18b20&serial=<?=$serial?>', function (data) {

				var temperature = [],
					dataLength = data.length;
				for (var i = 0; i < dataLength; i++) {
					temperature.push([data[i]["datetime"] * 1000, data[i]["temperature"]]);
				}
				
				
				Highcharts.stockChart('ds18b20_<?=$serial?>', {
					rangeSelector: rangeSelectorObj,
					
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

<?endforeach;?>