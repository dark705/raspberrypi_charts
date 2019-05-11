<?php
namespace DAO;

class ConfigIni{
	private $config;

	public function __construct ($file,$delimiter = '='){
		
		if ((file_exists($file)) and ($handle = fopen($file, "r")) !== false){
			while (($conf_arr = fgetcsv($handle, 1024, $delimiter)) !== false){
				$this->config[trim($conf_arr[0])] = trim($conf_arr[1]);
			}
			fclose ($handle);
		}
	}
	
	public function get($param){
		if (array_key_exists($param, $this->config))
			return $this->config[$param];
		else 
			return null;
	}
	
	public function __get($param){
		if (array_key_exists($param, $this->config))
			return $this->config[$param];
		else 
			return null;
	}
}
