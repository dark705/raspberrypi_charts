<?php
class mMySQL{
	private $dbHost;
	private $dbName;
	private $dbLogin;
	private $dbPass;
	
	public function __construct($host, $name, $login, $pass, $charset = 'utf8'){
		$this->dbHost = $host; 
		$this->dbName = $name;
		$this->dbLogin = $login;
		$this->dbPass = $pass;
		$this->dbCharset = $charset;
	}
	
	public function request($query){
		$mysqli = new mysqli($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName);
		if ($mysqli->connect_errno)
			die ("Ошибка соединения:$mysqli->connect_errno!<br> Host:$this->dbHost<br> DB:$this->dbName<br> Login: $this->dbLogin");
		else{
			$mysqli->set_charset($this->dbCharset);
			$result = $mysqli->query($query);
			$mysqli->close();
			unset($mysqli);
			return $result;
		}
	}
}
?>