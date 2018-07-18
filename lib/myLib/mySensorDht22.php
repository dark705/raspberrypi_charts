<?php
class mySensorDht22{
	private $my;
	
	public function __construct($my){
		date_default_timezone_set( 'UTC' );
		$this->my = $my;
	}
	
	private function result($query){
		$result = $this->my->request($query);
		while ($record = $result->fetch_row()){
			$all[] =  array('datetime' => strtotime($record[0]), 'temperature' => (float)$record[1], 'humidity' => (float)$record[2]);
		}
		return $all;
	}
	  
	public function get(){
		$query = "SELECT `datetime`, `temperature`, `humidity` FROM `dht22` WHERE `datetime` > NOW() - INTERVAL 31 DAY;"; 
		return $this->result($query);
	}
	
	public function getLast(){
		$query = "SELECT `datetime`, `temperature`, `humidity` FROM `dht22` ORDER BY `datetime` DESC LIMIT 0,1;";
		return $this->result($query);
	}
}

?>