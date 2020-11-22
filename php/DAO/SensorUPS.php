<?php

namespace DAO;

class SensorUPS
{
    private $my;

    public function __construct($my)
    {
        date_default_timezone_set('UTC');
        $this->my = $my;
    }

    public function get($serial)
    {
        $query = "SELECT `datetime`, `battery_charge`,`input_voltage`,`output_voltage`,`ups_load`,`ups_status` FROM `ups` WHERE `name` = '$serial' and `datetime` > NOW() - INTERVAL 31 DAY  ORDER BY `datetime`";
        return $this->result($query);
    }

    public function getLast($serial)
    {
        $query = "SELECT `datetime`, `battery_charge`,`input_voltage`,`output_voltage`,`ups_load`,`ups_status` FROM `ups` where `name` = '$serial' ORDER BY `datetime` DESC LIMIT 0,1;";
        return $this->result($query);
    }

    private function result($query)
    {
        $result = $this->my->request($query);

        while ($record = $result->fetch_assoc()) {
            $data[] = [strtotime($record['datetime']), (float) $record['battery_charge'], (float) $record['input_voltage'], (float) $record['output_voltage'], (float) $record['ups_load'], $record['ups_status']];
        }
        $types = ['datetime' => 0, 'battery_charge' => 1, 'input_voltage' => 2, 'output_voltage' => 3, 'ups_load' => 4, 'ups_status' => 5];

        return [
            'types' => $types,
            'data'  => $data
        ];
    }
}
