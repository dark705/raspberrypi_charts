<?php

namespace DAO;

class SensorBmp280
{
    private $my;

    public function __construct($my)
    {
        date_default_timezone_set('UTC');
        $this->my = $my;
    }

    public function get()
    {
        $query = "SELECT `datetime`, `pressure`, `temperature` FROM `bmp280` WHERE `datetime` > NOW() - INTERVAL 31 DAY;";
        return $this->result($query);
    }

    public function getLast()
    {
        $query = "SELECT `datetime`, `pressure`, `temperature` FROM `bmp280` ORDER BY `datetime` DESC LIMIT 0,1;";
        return $this->result($query);
    }

    private function result($query)
    {
        $result = $this->my->request($query);
        while ($record = $result->fetch_assoc()) {
            $data[] = [strtotime($record['datetime']), (float)$record['pressure'], (float)$record['temperature']];
        }
        $types = ['datetime' => 0, 'pressure' => 1, 'temperature' => 2];

        return [
            'types' => $types,
            'data' => $data
        ];
    }
}
