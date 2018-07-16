<div id="pzem004t" style="height: 800px; min-width: 310px"></div>
<script>
		$.getJSON('json/json.php?sensor=pzem004t', function (data) {
				
				// split the data set into voltage and current
				
				var voltage = [], current = [], active = [];

				$.each(data, function(index, value){
					voltage.push([value.datetime * 1000, value.voltage]);
					current.push([value.datetime * 1000, value.current]);
					active.push([value.datetime * 1000, value.active]);
				});

				/*
				for (var i = 0; i < data.length; i++) {
					voltage.push([data[i].datetime * 1000, data[i].voltage]);
					current.push([data[i].datetime * 1000, data[i].current]);
					active.push([data[i].datetime * 1000, data[i].active]);
				}
				*/
				// create the chart
				Highcharts.stockChart('pzem004t', {

					rangeSelector: {
						selected: 1,
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

					title: {
						text: 'Электросеть'
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
					
					xAxis: {
						type: 'datetime',
						ordinal: false,
						events: {
						  afterSetExtremes: updateLegendLabel
						}
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
						data: voltage,
						yAxis: 0
					}, {
						type: 'spline',
						name: 'Ампер',
						data: current,
						yAxis: 1
					}, {
						type: 'spline',
						name: 'Ватт',
						data: active,
						yAxis: 2
					}]
				});
			});

</script>