<?php
require_once('../php/autoload.php');

use DAO\SensorsFactory;
use Models\Template;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parseFile('../config/config.yaml');

if ($_POST) {
    header("Content-Type: application/json");
    $validSensors = array('pzem004t', 'dht22', 'ds18b20');

    if (!array_key_exists('sensor', $_POST))
        exit ('Please choose sensor name, by POST request');

    if (!in_array($_POST['sensor'], $validSensors))
        exit('invalid sensor name');


    $sensor = SensorsFactory::create($_POST['sensor'], $config['web']);

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
    $sensor_pzem004t = SensorsFactory::create('pzem004t', $config['web']);
    $sensor_dht22 = SensorsFactory::create('dht22', $config['web']);
    $sensor_ds18b20 = SensorsFactory::create('ds18b20', $config['web']);

    $html_last = new Template('../php/template/last.php', array('pzem004t' => $sensor_pzem004t, 'dht22' => $sensor_dht22, 'ds18b20' => $sensor_ds18b20));
    $html_pzem004t = new Template('../php/template/pzem004t.php');
    $html_dht22 = new Template('../php/template/dht22.php');
    $html_ds18b20 = new Template('../php/template/ds18b20.php', array('ds18b20' => $sensor_ds18b20));

    $html_common = new Template('../php/template/common.php', array('last' => $html_last->get(), 'pzem004t' => $html_pzem004t->get(), 'dht22' => $html_dht22->get(), 'ds18b20' => $html_ds18b20->get()));

    $html_common->show();
}
