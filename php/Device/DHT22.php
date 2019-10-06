<?php

namespace Device;

use Psr\Log\LoggerInterface;

class DHT22
{
    private $pin;
    private $execute;
    private $data;
    private $output;

    public function __construct($device, LoggerInterface $output)
    {
        $this->pin     = $device['pin'];
        $this->execute = __DIR__ . $device['loldht'];
        $this->data    = ['temperature' => false, 'humidity' => false];
        $this->output  = $output;
    }

    private function update()
    {
        $execResult = exec("sudo $this->execute $this->pin");
        if (preg_match_all('/\-?\d{1,3}\.\d{1}/', $execResult, $execResultMatch) == 2) {
            $this->data['temperature'] = $execResultMatch[0][1];
            $this->data['humidity'] = $execResultMatch[0][0];
            $this->output->debug($execResult);
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