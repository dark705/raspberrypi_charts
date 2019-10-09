<?php

namespace Device;

//Peacefair(object Serial)->getData() - return array with keys "voltage", "current", "active", "energy" = currient values
//Peacefair->voltage - get "voltage"...
use Psr\Log\LoggerInterface;

class Peacefair
{
    private $dev;
    private $data;
    private $output;

    public function __construct(Serial $dev, LoggerInterface $output)
    {
        $this->dev    = $dev;
        $this->data   = array();
        $this->output = $output;
    }

    private function updateVoltage($tx = 'B0C0A80101001A')
    {
        //voltage
        if ($rx = $this->dev->txrx(hex2bin($tx))) {
            $arr = str_split(bin2hex($rx), 2);
            $this->data['voltage'] = hexdec($arr[1] . $arr[2]) + hexdec($arr[3]) / 10;
            if ($this->output) {
                $this->showDebug($tx, $rx);
            }
        } else {
            $this->data['voltage'] = false;
        }
    }

    private function updateCurrent($tx = 'B1C0A80101001B')
    {
        //current
        if ($rx = $this->dev->txrx(hex2bin($tx))) {
            $arr = str_split(bin2hex($rx), 2);
            $this->data['current'] = hexdec($arr[2]) + hexdec($arr[3]) / 100;
            if ($this->output) {
                $this->showDebug($tx, $rx);
            }
        } else {
            $this->data['current'] = false;
        }

    }

    private function updateActive($tx = 'B2C0A80101001C')
    {
        //active
        if ($rx = $this->dev->txrx(hex2bin($tx))) {
            $arr = str_split(bin2hex($rx), 2);
            $this->data['active'] = hexdec($arr[1] . $arr[2]);
            if ($this->output) {
                $this->showDebug($tx, $rx);
            }
        } else {
            $this->data['active'] = false;
        }
    }

    private function updateEnergy($tx = 'B3C0A80101001D')
    {
        //energy
        if ($rx = $this->dev->txrx(hex2bin($tx))) {
            $arr = str_split(bin2hex($rx), 2);
            $this->data['energy'] = hexdec($arr[1] . $arr[2] . $arr[3]);
            if ($this->output) {
                $this->showDebug($tx, $rx);
            }
        } else {
            $this->data['energy'] = false;
        }
    }

    private function update()
    {
        $this->updateVoltage();
        $this->updateCurrent();
        $this->updateActive();
        $this->updateEnergy();
    }

    public function __get($param)
    {
        $param = strtolower($param);
        $method = 'update' . ucfirst($param);
        if (method_exists($this, $method)) {//if requested parameter exist
            $this->$method();//update value of this patameter
            return $this->data[$param];//and return result
        } else {
            return false;
        }
    }

    public function getData()
    {
        $this->update();
        if ($this->check()) {
            return $this->data;
        } else {
            return false;
        }
    }

    private function check()
    {
        foreach ($this->data as $param) {
            if ($param === false) {
                return false;
            }
        }
        return true;
    }

    private function showDebug($tx, $rx)
    {
        $this->output->debug('Peacefair exchange data', ['tx'=> $tx, 'rx' => strtoupper(bin2hex($rx))]);
    }
}
