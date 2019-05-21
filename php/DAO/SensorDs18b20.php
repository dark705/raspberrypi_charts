<?php

namespace DAO;

class SensorDs18b20
{
    private $my;
    private $ds;

    public function __construct($my, $dsNames)
    {
        date_default_timezone_set('UTC');
        $this->my = $my;
        $result = $this->my->request("SELECT DISTINCT `serial` FROM `ds18b20`;");
        while ($record = $result->fetch_row()) {
            if (isset($dsNames[$record[0]]))
                $this->ds[$record[0]] = $dsNames[$record[0]];
            else
                $this->ds[$record[0]] = $record[0];
        }

    }

    public function get($serial)
    {
        if (!array_key_exists($serial, $this->ds))
            exit("no sensor with serial $serial");
        $query = "SELECT `datetime`, `temperature` FROM `ds18b20` WHERE `serial` = '$serial' AND `datetime` > NOW() - INTERVAL 31 DAY;";
        return $this->result($query);
    }

    public function getLast($serial)
    {
        if (!array_key_exists($serial, $this->ds))
            exit("no sensor with serial $serial");
        $query = "SELECT `datetime`, `temperature` FROM `ds18b20` WHERE `serial` = '$serial' ORDER BY `datetime` DESC LIMIT 0,1;";
        return $this->result($query);
    }

    public function getNames()
    {
        return $this->ds;
    }

    private function result($query)
    {
        $result = $this->my->request($query);
        while ($record = $result->fetch_assoc()) {
            $data[] = [strtotime($record['datetime']), (float)$record['temperature']];
        }
        $types = ['datetime' => 0, 'temperature' => 1];

        return [
            'types' => $types,
            'data' => $data
        ];
    }
}
