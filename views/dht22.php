<div id="dht22" style="height: 500px; min-width: 310px"></div>
<script>
		$.getJSON('json/json.php?sensor=dht22', function (data) {

				// split the data set into temperature and humidity
				var temperature = [], humidity = [];

				for (var i = 0; i < data.length; i++) {
					temperature.push([
						data[i]["datetime"] * 1000, // the date
						data[i]["temperature"], // temperature
					]);

					humidity.push([
						data[i]["datetime"] * 1000, // the date
						data[i]["humidity"] // the humidity
					]);
				}


				// create the chart
				Highcharts.stockChart('dht22', {

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
						text: 'Погода'
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
						data: temperature,
						yAxis: 0
					}, {
						type: 'spline',
						name: '%',
						data: humidity,
						yAxis: 1
					}]
				});
			});

</script>