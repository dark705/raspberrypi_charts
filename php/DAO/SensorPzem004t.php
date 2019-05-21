<?php

namespace DAO;

class SensorPzem004t
{
    private $my;

    public function __construct($my)
    {
        date_default_timezone_set('UTC');
        $this->my = $my;
    }

    public function get()
    {
        $query = "SELECT `datetime`, `voltage`, `current`,`active` FROM `pzem004t` WHERE `datetime` > NOW() - INTERVAL 31 DAY;";
        return $this->result($query);
    }

    public function getLast()
    {
        $query = "SELECT `datetime`, `voltage`, `current`,`active` FROM `pzem004t` ORDER BY `datetime` DESC LIMIT 0,1;";
        return $this->result($query);
    }

    private function result($query)
    {
        $result = $this->my->request($query);

        while ($record = $result->fetch_assoc()) {
            $data[] = [strtotime($record['datetime']), (float)$record['voltage'], (float)$record['current'], (float)$record['active']];

        }
        $types = ['datetime' => 0, 'voltage' => 1, 'current' => 2, 'active' => 3];

        return [
            'types' => $types,
            'data' => $data
        ];
    }
}
