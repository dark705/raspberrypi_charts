<?php
namespace Device;

//mDS18B20($debug)->getAll() - return array with keys as "serial" and value as temperature
//mDS18B20->getTemp($serial) - get "temperature" of device with setial
class DS18B20{
	private $devs;
	private $dirr;
	private $data;
	private $debug;
	
	public function __construct($debug = false){
		$this->debug = $debug;
		$this->devs = false;
		$this->dirr = '/sys/bus/w1/devices/';
		$this->data = false;
	}
	
	//search available device's
	private function getDevs(){
		$dirs = scandir($this->dirr);
		foreach($dirs as $dir){
			if (preg_match('/[0-9a-f]{2}\-[0-9a-f]{12}/i',$dir, $dev)){
				$this->devs[] = $dev[0];
				if ($this->debug)
					echo "Found device: $dev[0]" . PHP_EOL;
			}
				
		}
	}
	
	private function update(){
		if ($this->devs){
			foreach($this->devs as $dev){
				$content = file_get_contents($this->dirr . $dev . '/w1_slave');
				if ($this->debug){
					echo "Device: $dev " . PHP_EOL;
					echo "Content: $content " . PHP_EOL;
				}
				if (preg_match('/t=\d{4}/i',$content, $temperature)){
					$temperature = round(str_replace('t=', '', $temperature[0])/100, 1);
					$this->data[$dev] = $temperature;
				}
				else
					$this->data[$dev] = false;
			}
		}
		else
			$this->data = false;
	}
	
	public function getTemp($ser){
		$this->getDevs();
		$this->update();
		if (array_key_exists($ser, $this->data)){
			return $this->data[$ser];
		}
		return false;
	}

	public function getAll(){
		$this->getDevs();
		$this->update();
		return $this->data;
	}
}
/*
 //debug
$t = new mDS18B20(false);
var_dump($t->getAll());
*/
?>