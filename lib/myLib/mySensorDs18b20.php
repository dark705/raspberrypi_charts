<?php
class mySensorDs18b20{
	private $my;
	private $ds;
	
	public function __construct($my, $dsNames){
		date_default_timezone_set( 'UTC' );
		$this->my = $my;
		$result = $this->my->request("SELECT DISTINCT `serial` FROM `ds18b20`;");
		while ($record = $result->fetch_row()){
			if ($dsNames->get($record[0]))
				$this->ds[$record[0]] = $dsNames->get($record[0]);
			else
				$this->ds[$record[0]] = $record[0];
		}
		
	}
	
	private function result($query){
		$result = $this->my->request($query);
		while ($record = $result->fetch_row()){
			$all[] =  array('datetime' => strtotime($record[0]), 'temperature' => (float)$record[1]);
		}
		return $all;
	}
	  
	public function get($serial){
		if (!array_key_exists($serial, $this->ds))
			exit("no sensor with serial $serial");
		$query = "SELECT `datetime`, `temperature` FROM `ds18b20` WHERE `serial` = '$serial' AND `datetime` > NOW() - INTERVAL 31 DAY;"; 
		return $this->result($query);
	}
	
	public function getLast($serial){
		if (!array_key_exists($serial, $this->ds))
			exit("no sensor with serial $serial");
		$query = "SELECT `datetime`, `temperature` FROM `ds18b20` WHERE `serial` = '$serial' ORDER BY `datetime` DESC LIMIT 0,1;";
		return $this->result($query);
	}
	
	public function getNames(){
		return $this->ds;
	}
}
?>