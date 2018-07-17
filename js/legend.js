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

var rangeSelectorObj = {
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
							text: 'нед'
						}, {
							type: 'month',
							count: 1,
							text: 'мес'
						}]
					}