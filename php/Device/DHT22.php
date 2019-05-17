<?php
namespace Device;

//mDHT22($exe, $pin)->getData() - return array with keys "humidity", "temperature"
//mDHT22->temperature - get "temperature"...
class DHT22{
	private $pin;
	private $exe;
	private $debug;
	private $data;
	
	public function __construct($exe, $pin, $debug = true){
		$this->pin = $pin;
		$this->exe = $exe;
		$this->debug = $debug;
		$this->data = array('temperature' => false, 'humidity' => false);
	}

	private function update(){
		$dht = exec("sudo $this->exe $this->pin");
		if (preg_match_all('/\-?\d{1,3}\.\d{1}/', $dht, $dht_arr) == 2){
			$this->data['temperature'] = $dht_arr[0][1];
			$this->data['humidity'] = $dht_arr[0][0];
		}
		else
			$this->data['temperature'] = $this->data['humidity'] = false;
	}
	
	public function __get($param){
		if (array_key_exists($param, $this->data)){
			$this->update();
			return $this->data[$param];
		}
		return false;
	}

	public function getData(){
		$this->update();
		if ($this->check())
			return $this->data;
		else
			return false;
	}
	
	private function check(){
		foreach ($this->data as $param){
			if ($param === false)
				return false;
		}
		return true;
	}
}
/* //debug
$t = new mDHT22('/var/www/html/voltage/lib/loldht/loldht', 7);
var_dump($t->getData());
*/
?>