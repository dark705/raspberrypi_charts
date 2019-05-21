<?php foreach($ds18b20->getNames() as $serial => $name):?>
<div class="chart">
	<a name="chart__<?=$serial?>"></a>
	<div id="ds18b20_<?=$serial?>" style="height: 400px; min-width: 310px">Загрузка данных...</div>
</div>
<script>
			
			chartDs18b20__<?=md5($serial)?> =	Highcharts.stockChart('ds18b20_<?=$serial?>', {
					rangeSelector: rangeSelectorObj,

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
						type: 'spline',
						tooltip: {
							valueDecimals: 2
						}
					}]
				});
			chartDs18b20__<?=md5($serial)?>.showLoading();
			
			$.post('', {sensor: 'ds18b20', serial: '<?=$serial?>'}, function (response) {
                var temperature = [];
                var index = response.types;
                $.each(response.data, function (i, data) {
                    temperature.push([data[index.datetime] * 1000, data[index.temperature]]);
                });
                
				chartDs18b20__<?=md5($serial)?>.series[0].setData(temperature);
				chartDs18b20__<?=md5($serial)?>.hideLoading();
			});
</script>
<?php endforeach;?>