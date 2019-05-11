<div class="chart">
	<a name="chart__weather"></a>
	<div id="dht22" style="height: 500px; min-width: 310px"></div>
</div>
<script>
				var chartDht22 = Highcharts.stockChart('dht22', {

					rangeSelector: rangeSelectorObj,

					title: {
						text: 'Погода'
					},
					
					legend: {
						enabled: true,
						floating: false,
						verticalAlign: 'bottom',
						useHTML: true
					},
					
					xAxis: {
						type: 'datetime',
						ordinal: false
					},

					yAxis: [{
						labels: {
							align: 'right',
							x: -3
						},
						title: {
							text: 'Температура'
						},
						height: '60%',
						lineWidth: 1,
						resize: {
							enabled: true
						}
					}, {
						labels: {
							align: 'right',
							x: -3
						},
						title: {
							text: 'Влажность'
						},
						top: '65%',
						height: '30%',
						offset: 0,
						lineWidth: 1
					}],
					
					plotOptions: {
						spline: {
							marker: {
								enabled: true
							}
						}
					},

					tooltip: {
						split: true
					},

					series: [{
						type: 'spline',
						name: 'С°',
						yAxis: 0
					}, {
						type: 'spline',
						name: '%',
						yAxis: 1
					}]
				});
				
		chartDht22.showLoading();
		$.getJSON('json.php?sensor=dht22', function (data) {
			
			var temperature = [], humidity = [];
			$.each(data, function(index, value){
				temperature.push([value.datetime * 1000, value.temperature]);
				humidity.push([value.datetime * 1000, value.humidity]);	
			});
			
			chartDht22.series[0].setData(temperature,false);
			chartDht22.series[1].setData(humidity,true);
			chartDht22.hideLoading();
			
		});
</script>
