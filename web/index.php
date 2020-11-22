<?php
require_once('../vendor/autoload.php');

use DAO\SensorsFactory;
use Models\Template;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('../config/config.web.yaml');

if ($_POST) {
    header("Content-Type: application/json");
    $validSensors = ['pzem004t', 'dht22', 'ds18b20', 'bmp280', 'ups'];

    if (!array_key_exists('sensor', $_POST))
        exit ('Please choose sensor name, by POST request');

    if (!in_array($_POST['sensor'], $validSensors))
        exit('invalid sensor name');


    $sensor = SensorsFactory::create($_POST['sensor'], $config);

    if (array_key_exists('names', $_POST)) {
        echo json_encode($sensor->getNames());
        exit();
    }

    if (array_key_exists('serial', $_POST)) {
        $serial = $_POST['serial'];
    } else {
        $serial = null;
    }


    if (array_key_exists('last', $_POST)) {
        echo json_encode($sensor->getLast($serial));
    } else {
        echo json_encode($sensor->get($serial));
    }
} else {
    $sensor_pzem004t = SensorsFactory::create('pzem004t', $config);
    $sensor_dht22    = SensorsFactory::create('dht22', $config);
    $sensor_ds18b20  = SensorsFactory::create('ds18b20', $config);
    $sensor_bmp280   = SensorsFactory::create('bmp280', $config);
    $sensor_ups      = SensorsFactory::create('ups', $config);

    $templateLast = new Template(
        '../php/template/last.php',
        [
            'pzem004t' => $sensor_pzem004t,
            'dht22'    => $sensor_dht22,
            'ds18b20'  => $sensor_ds18b20,
            'bmp280'   => $sensor_bmp280,
            'ups'      => $sensor_ups,
        ]
    );

    $templatePzem004t    = new Template('../php/template/pzem004t.php');
    $templateWeather = new Template('../php/template/weather.php');
    $templateDs18b20    = new Template('../php/template/ds18b20.php', array('ds18b20' => $sensor_ds18b20));
    $templateUps      = new Template('../php/template/ups.php');

    $htmlCommon = new Template(
        '../php/template/common.php',
        [
            'last'     => $templateLast->get(),
            'pzem004t' => $templatePzem004t->get(),
            'weather'  => $templateWeather->get(),
            'ds18b20'  => $templateDs18b20->get(),
            'ups'      => $templateUps->get(),
        ]
    );

    $htmlCommon->show();
}
