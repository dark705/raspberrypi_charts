<div class="chart">
	<a name="chart__electro"></a>
	<div id="pzem004t" style="height: 800px; min-width: 310px"></div>
</div>
<script>
				var chartPzem004t = Highcharts.stockChart('pzem004t', {
					rangeSelector: rangeSelectorObj,

					title: {
						text: 'Электросеть'
					},
					
					legend: {
						enabled: true,
						floating: false,
						verticalAlign: 'bottom',
						useHTML: true
					},
					
					xAxis: {
						type: 'datetime',
						ordinal: false,
					},

					yAxis: [{
						labels: {
							align: 'right',
							x: -3
						},
						title: {
							text: 'Напряжение'
						},
						height: '50%',
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
							text: 'Ток'
						},
						top: '52%',
						height: '20%',
						offset: 0,
						lineWidth: 1
					}, {
						labels: {
							align: 'right',
							x: -3
						},
						title: {
							text: 'Мощьность'
						},
						top: '75%',
						height: '20%',
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
						name: 'Вольт',
						yAxis: 0
					}, {
						type: 'spline',
						name: 'Ампер',
						yAxis: 1
					}, {
						type: 'spline',
						name: 'Ватт',
						yAxis: 2
					}]
				});
			 chartPzem004t.showLoading();
	
		$.getJSON('json.php?sensor=pzem004t', function (data) {
				
				var voltage = [], current = [], active = [];
				$.each(data, function(index, value){
					voltage.push([value.datetime * 1000, value.voltage]);
					current.push([value.datetime * 1000, value.current]);
					active.push([value.datetime * 1000, value.active]);
				});
				
				chartPzem004t.series[0].setData(voltage,false);
				chartPzem004t.series[1].setData(current,false);
				chartPzem004t.series[2].setData(active,true);
				chartPzem004t.hideLoading();

			});

</script>
