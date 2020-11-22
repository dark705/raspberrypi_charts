<div class="chart">
    <a name="chart__ups"></a>
    <div id="ups" style="height: 800px; min-width: 310px"></div>
</div>
<script>
    var chartUps = Highcharts.stockChart('ups', {
        rangeSelector: rangeSelectorObj,

        title: {
            text: 'ИБП Насосы'
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
                text: 'Вход Напряжение'
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
                text: 'Выход Напряжение'
            },
            height: '50%',
            lineWidth: 1
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Нагрузка'
            },
            top: '52%',
            height: '25%',
            offset: 0,
            lineWidth: 1
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Батарея'
            },
            top: '78%',
            height: '25%',
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
            name: 'Вольт Вход',
            yAxis: 0,
        }, {
            type: 'spline',
            name: 'Вольт Выход',
            yAxis: 0
        }, {
            type: 'spline',
            name: '%',
            yAxis: 2
        }, {
            type: 'spline',
            name: '%',
            yAxis: 3
        }]
    });
    chartUps.showLoading();

    $.post('', {sensor: 'ups',  serial: 'powercom'}, function (response) {
        var voltageIn = [], voltageOut = [], load = [], bat = [];
        var index = response.types;
        $.each(response.data, function (i, data) {

            voltageIn.push([data[index.datetime] * 1000, data[index.input_voltage]]);
            voltageOut.push([data[index.datetime] * 1000, data[index.output_voltage]]);
            load.push([data[index.datetime] * 1000, data[index.ups_load]]);
            bat.push([data[index.datetime] * 1000, data[index.battery_charge]]);
        });

        chartUps.series[0].setData(voltageIn, false);
        chartUps.series[1].setData(voltageOut, false);
        chartUps.series[2].setData(load, false);
        chartUps.series[3].setData(bat, true);
        chartUps.hideLoading();
    });
</script>
