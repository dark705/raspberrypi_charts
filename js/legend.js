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
