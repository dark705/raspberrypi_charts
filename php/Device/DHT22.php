<?php

namespace Device;

class DHT22
{
    private $pin;
    private $execute;
    private $data;
    private $debug;

    public function __construct($device, $debug = false)
    {
        $this->pin = $device['pin'];
        $this->execute = __DIR__ . $device['loldht'];
        $this->data = ['temperature' => false, 'humidity' => false];
        $this->debug = $debug;
    }

    private function update()
    {
        $dht = exec("sudo $this->execute $this->pin");
        if (preg_match_all('/\-?\d{1,3}\.\d{1}/', $dht, $dht_arr) == 2) {
            $this->data['temperature'] = $dht_arr[0][1];
            $this->data['humidity'] = $dht_arr[0][0];
            if ($this->debug) {
                echo $dht . PHP_EOL;
            }
        } else {
            $this->data['temperature'] = $this->data['humidity'] = false;
        }
    }

    public function __get($param)
    {
        if (array_key_exists($param, $this->data)) {
            $this->update();
            return $this->data[$param];
        }
        return false;
    }

    public function getData()
    {
        $this->update();
        if ($this->check())
            return $this->data;
        else
            return false;
    }

    private function check()
    {
        foreach ($this->data as $param) {
            if ($param === false)
                return false;
        }
        return true;
    }
}
/*
$s = new DHT22(['loldht' => 'loldht', 'pin' => 7]);
var_dump($s->getData()); // return array with keys "humidity", "temperature"
$s->temperature; //- get "temperature"..
*/