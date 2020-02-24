<div class="chart">
    <a name="chart__weather"></a>
    <div id="weather" style="height: 700px; min-width: 310px"></div>
</div>
<script>
    var chartWeather = Highcharts.stockChart('weather', {

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
            height: '40%',
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
            top: '43%',
            height: '28%',
            offset: 0,
            lineWidth: 1
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Давление'
            },
            top: '75%',
            height: '28%',
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
        }, {
            type: 'spline',
            name: 'мм',
            yAxis: 2
        }]
    });

    chartWeather.showLoading();
    $.post('', {sensor: 'dht22'}, function (response) {
        var temperature = [], humidity = [];
        var index = response.types;
        $.each(response.data, function (i, data) {
            temperature.push([data[index.datetime] * 1000, data[index.temperature]]);
            humidity.push([data[index.datetime] * 1000, data[index.humidity]]);
        });

        chartWeather.series[0].setData(temperature, false);
        chartWeather.series[1].setData(humidity, true);
        chartWeather.hideLoading();
    });

    $.post('', {sensor: 'bmp280'}, function (response) {
        var pressure = [];
        var index = response.types;
        $.each(response.data, function (i, data) {
            pressure.push([data[index.datetime] * 1000, data[index.pressure]]);
        });
        chartWeather.series[2].setData(pressure, true);
    });
</script>
