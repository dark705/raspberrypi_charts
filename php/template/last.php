<div id="lasts">
    <!-- start electro last section -->
    <?php
    $lastE     = $pzem004t->getLast();
    $lastEData = $lastE['data'][0];
    ?>
    <div class="last">
        <a class="itemlink" href="#chart__electro">
            <div id="last__electro" class="item">
                <h3>Электросеть:</h3>
                <p id="last__electro__time" class="ontime">(показания на:
                    <span><?= gmdate("Y-m-d H:i:s", $lastEData[$lastE['types']['datetime']]) ?></span>)</p>
                <p id="last__electro__voltage">Напряжение: <span><?= $lastEData[$lastE['types']['voltage']] ?></span>
                </p>
                <p id="last__electro__current">Ток: <span><?= $lastEData[$lastE['types']['current']] ?></span></p>
                <p id="last__electro__active">Мощность: <span><?= $lastEData[$lastE['types']['active']] ?></span></p>

            </div>
        </a>
    </div>
    <!-- end -->
    <!-- start weather last section -->
    <?php
    $lastW      = $dht22->getLast();
    $lastWData  = $lastW['data'][0];
    $lastWP     = $bmp280->getLast();
    $lastWPData = $lastWP['data'][0];
    ?>
    <div class="last">
        <a class="itemlink" href="#chart__weather">
            <div id="last__weather" class="item ">
                <h3>Погода:</h3>
                <p id="last__weather__time" class="ontime">
                    (показания на:
                    <span><?= gmdate("Y-m-d H:i:s", $lastWData[$lastW['types']['datetime']]) ?></span>
                    )
                </p>
                <p id="last__weather__temp">Температура: <span><?= $lastWData[$lastW['types']['temperature']] ?></span>
                </p>
                <p id="last__weather__humidity">Влажность: <span><?= $lastWData[$lastW['types']['humidity']] ?></span>
                </p>
                <p id="last__weather__time__pressure" class="ontime">
                    (показания на:
                    <span><?= gmdate("Y-m-d H:i:s", $lastWPData[$lastW['types']['datetime']]) ?></span>
                    )
                </p>
                <p id="last__weather__pressure">Давление: <span><?= $lastWPData[$lastWP['types']['pressure']] ?></span>
                </p>
            </div>
        </a>
    </div>
    <!-- end -->
    <!-- start ds18b20 last section -->
    <?php foreach ($ds18b20->getNames() as $serial => $name): ?>
        <div class="last">
            <a class="itemlink" href="#chart__<?= $serial ?>">
                <div id="<?= $serial ?>" class="item last__ds18b20">
                    <h3><?= $name ?></h3>
                    <?php
                    $lastD     = $ds18b20->getLast($serial);
                    $lastDData = $lastD['data'][0];
                    ?>
                    <p class="last__ds18b20__time ontime">(показания на:
                        <span><?= gmdate("Y-m-d H:i:s", $lastDData[$lastD['types']['datetime']]) ?></span>)</p>
                    <p class="last__ds18b20__temp">Температура:
                        <span><?= $lastDData[$lastD['types']['temperature']] ?></span></p>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    <!-- end -->
    <div class="clear"></div>

    <script>
        'use strict';

        function updateLastElectro() {
            $.post('', {sensor: 'pzem004t', last: 'true'}, function (response) {
                var data = response.data[0];
                var index = response.types;

                //update last section
                var d = new Date((data[index.datetime] - 3 * 60 * 60) * 1000);
                var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
                $('#last__electro__time span').text(datetime);
                $('#last__electro__voltage span').text(data[index.voltage]);
                $('#last__electro__current span').text(data[index.current]);
                $('#last__electro__active span').text(data[index.active]);
                $('#last__electro').animate({opacity: 0.1}, 500).animate({opacity: 1.0}, 500)

                //update graph
                var chartPzem004t = $('#pzem004t').highcharts();
                var voltage = [data[index.datetime] * 1000, data[index.voltage]];
                var current = [data[index.datetime] * 1000, data[index.current]];
                var active = [data[index.datetime] * 1000, data[index.active]];
                chartPzem004t.series[0].addPoint(voltage, false, true);
                chartPzem004t.series[1].addPoint(current, false, true);
                chartPzem004t.series[2].addPoint(active, false, true);
                chartPzem004t.redraw();
            });
        }

        function updateLastWeather() {
            $.post('', {sensor: 'dht22', last: 'true'}, function (response) {
                var data = response.data[0];
                var index = response.types;

                //update last section
                var d = new Date((data[index.datetime] - 3 * 60 * 60) * 1000);
                var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
                $('#last__weather__time span').text(datetime);
                $('#last__weather__temp span').text(data[index.temperature]);
                $('#last__weather__humidity span').text(data[index.humidity]);
                $('#last__weather').animate({opacity: 0.1}, 500).animate({opacity: 1.0}, 500);

                //update graph
                var chartDht22 = $('#dht22').highcharts();
                var temperature = [data[index.datetime] * 1000, data[index.temperature]];
                var humidity = [data[index.datetime] * 1000, data[index.humidity]];
                chartDht22.series[0].addPoint(temperature, false, true);
                chartDht22.series[1].addPoint(humidity, false, true);
                chartDht22.redraw();
            });
        }

        function updateLastDs18b20() {
            $(".last__ds18b20").each(function (index, thisEl) {
                var serial = $(this).attr('id');
                $.post('', {sensor: 'ds18b20', serial: serial, last: 'true'}, function (response) {
                    var data = response.data[0];
                    var index = response.types;

                    //update last section
                    var d = new Date((data[index.datetime] - 3 * 60 * 60) * 1000);
                    var datetime = d.toString('yyyy-MM-dd HH:mm:ss');
                    $(thisEl).find('.last__ds18b20__time span').text(datetime);
                    $(thisEl).find('.last__ds18b20__temp span').text(data[index.temperature]);
                    $('#' + serial).animate({opacity: 0.1}, 500).animate({opacity: 1.0}, 500)

                    //update graph
                    var chartDs18b20__item = $('#ds18b20_' + serial).highcharts();
                    var temperature = [data[index.datetime] * 1000, data[index.temperature]];
                    chartDs18b20__item.series[0].addPoint(temperature, false, true);
                    chartDs18b20__item.redraw();
                });
            });
        }

        $(function () {
            setInterval(function () {
                updateLastElectro();
                updateLastWeather();
                updateLastDs18b20()
            }, 1 * 60 * 1000);
        });
    </script>
</div>