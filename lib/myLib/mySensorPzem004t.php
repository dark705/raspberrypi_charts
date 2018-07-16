<?php
class mySensorPzem004t{
	private $my;
	
	public function __construct($config){
		date_default_timezone_set( 'UTC' );
		$this->my = new mMySQL($config->dbHost, $config->dbName, $config->dbLogin,$config->dbPass);	
	}
	
	private function result($query){
		$result = $this->my->request($query);
		while ($record = $result->fetch_row()){
			$all[] =  array('datetime' => strtotime($record[0]), 'voltage' => (float)$record[1], 'current' => (float)$record[2], 'active' => (float)$record[3]);
		}
		return $all;
	}
	  
	public function get(){
		$query = "SELECT `datetime`, `voltage`, `current`,`active` FROM `pzem004t` WHERE `datetime` > NOW() - INTERVAL 5 DAY;"; 
		return $this->result($query);
	}
	
	public function getLast(){
		$query = "SELECT `datetime`, `voltage`, `current`,`active` FROM `pzem004t` ORDER BY `datetime` DESC LIMIT 0,1;";
		return $this->result($query);
	}
}

?>