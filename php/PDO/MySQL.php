<?php

namespace PDO;

class MySQL
{
    private $dbHost;
    private $dbName;
    private $dbLogin;
    private $dbPass;
    private $dbCharset;

    public function __construct($conf)
    {
        $this->dbHost = $conf['host'];
        $this->dbName = $conf['name'];
        $this->dbLogin = $conf['login'];
        $this->dbPass = $conf['pass'];
        if (isset($conf['charset'])) {
            $this->dbCharset = $conf['charset'];
        } else {
            $this->dbCharset = 'utf8';
        }

    }

    public function request($query)
    {
        $mysqli = new \mysqli($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName);
        if ($mysqli->connect_errno)
            die ("Ошибка соединения:$mysqli->connect_errno!<br> Host:$this->dbHost<br> DB:$this->dbName<br> Login: $this->dbLogin");
        else {
            $mysqli->set_charset($this->dbCharset);
            $result = $mysqli->query($query);
            $mysqli->close();
            unset($mysqli);
            return $result;
        }
    }
}
