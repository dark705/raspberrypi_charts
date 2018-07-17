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

					rangeSelector: rangeSelectorObj,

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