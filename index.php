<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Highstock Example</title>

		<style type="text/css">

		</style>
	</head>
	<body>
<script src="lib/jquery/jquery-3.2.1.min.js"></script>
<script src="lib/highstock/highstock.js"></script>
<script src="lib/highstock/themes/grid-light.js"></script>
<script src="lib/highstock/modules/exporting.js"></script>
<script src="lib/highstock/lang/ru.js"></script>

<div id="electro" style="height: 500px; min-width: 310px"></div>
<div id="dht22" style="height: 500px; min-width: 310px"></div>
<script>
function updateLegendLabel() {
  var chrt = !this.chart ? this : this.chart;
  chrt.update({
    legend: {
      labelFormatter: function() {
        var lastVal = this.yData[this.yData.length - 1],
          chart = this.chart,
          xAxis = this.xAxis,
          points = this.points,
          avg = 0,
          counter = 0,
          min, minPoint, max, maxPoint;

        points.forEach(function(point, inx) {
          if (point.isInside) {
            if (!min || min > point.y) {
              min = point.y;
              minPoint = point;
            }

            if (!max || max < point.y) {
              max = point.y;
              maxPoint = point;
            }

            counter++;
            avg += point.y;
          }
        });
        avg /= counter;

		
        return  this.name +
          ' (<span style="color: red">макс:' + max.toFixed(1) + '</span> - ' +
		  '<span style="color: blue">мин:' + min.toFixed(1) + ' </span>' +
          '<span style="color: green">сред: ' + avg.toFixed(2) + '</span>)';
      }
    }
  });
}
</script>


<script>
		$.getJSON('jsonp.php', function (data) {

				// split the data set into voltage and current
				var voltage = [],
					current = [],
					active = [],
					dataLength = data.length,

				i = 0;
				for (i; i < dataLength; i += 1) {
					voltage.push([
						data[i][0] * 1000, // the date
						data[i][1], // voltage
					]);

					current.push([
						data[i][0] * 1000, // the date
						data[i][2] // the current
					]);
					
					active.push([
						data[i][0] * 1000, // the date
						data[i][3] // the active power
					]);
				}


				// create the chart
				Highcharts.stockChart('electro', {

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

<script>
		$.getJSON('jsond.php', function (data) {

				// split the data set into temperature and humidity
				var temperature = [],
					humidity = [],
					dataLength = data.length,

				i = 0;
				for (i; i < dataLength; i += 1) {
					temperature.push([
						data[i][0] * 1000, // the date
						data[i][1], // temperature
					]);

					humidity.push([
						data[i][0] * 1000, // the date
						data[i][2] // the humidity
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


	</body>
</html>
