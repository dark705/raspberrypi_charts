<?php
namespace Device;

//Serial(device, debug (bool), initial)
class Serial{
	private $dev;
	private $debug;
	private $initial;
	
	public function __construct($dev, $debug = false, $initial = '9600 raw -echo'){
		$this->dev = $dev;
		$this->debug = $debug;
		$this->initial = $initial;
	}
	
	public function txrx($tx){
		if (file_exists($this->dev)){
			exec('stty -F '.$this->dev.' '.$this->initial);
			if ($handle = fopen($this->dev, "r+")){
				fwrite($handle, $tx);
				$rx = fread($handle, 7 );
				fclose($handle);
				if ($this->debug)
					printf('tx(hex):%s rx(hex): %s correct:%s'.PHP_EOL, bin2hex($tx), bin2hex($rx), $this->check($rx));
				if ($this->check($rx) === true)
					return $rx;
			}
		}
	return false;
	}
	
	private function check($in){
		if($in){
			$arr = str_split(bin2hex($in),2);
			$summ = 0;
			for ($i = 0; $i <= 5; $i++)
				$summ += hexdec($arr[$i]); //hex to dec	
			if (substr(dechex($summ),-2) == $arr[6]) //last 2 chr. from hex 
				return true;
		}
		return false;
	}
}
?>