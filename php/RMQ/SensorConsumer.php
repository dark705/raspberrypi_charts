<?php

namespace RMQ;

use Exception;
use PDO\MySQL;

class SensorConsumer extends SimpleConsumer
{
    /** @var  MySQL */
    private $pdo;

    public function setPDO(MySQL $pdo)
    {
        $this->pdo = $pdo;
    }

    public function processMessage($msg)
    {
        parent::processMessage($msg);

        $mes = json_decode($msg->body, true);
        $this->output->info('Data from', ['sensor' => $mes['sensor']]);

        switch ($mes['sensor']) {
            case 'peacefair':
                $query = sprintf("INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) VALUES ('%s', '%s', '%s', '%s', '%s');",
                                 $mes['date'], $mes['data']['voltage'], $mes['data']['current'], $mes['data']['active'], $mes['data']['energy']);
                break;
            case 'dht22':
                $query = sprintf("INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) VALUES ('%s', '%s', '%s');",
                                 $mes['date'], $mes['data']['temperature'], $mes['data']['humidity']);
                break;
            case 'ds18b20':
                $query = sprintf("INSERT INTO `ds18b20` (`datetime`, `serial`, `temperature`) VALUES ('%s', '%s', '%s');",
                                 $mes['date'], $mes['data']['serial'], $mes['data']['temperature']);
                break;
            case 'bmp280': //TODO
                $this->output->notice("Mast implement BMP280");
                $this->sendAck($msg);
                return;
            default:
                throw new Exception('Unknown sensor in RMQ message');
        }
        $this->output->debug('SQL query: ', ['query' => $query]);

        if ($this->pdo->request($query)) {
            $this->output->debug('Success MySQL insert');
            if ($this->config['ack']) {
                $this->sendAck($msg);
            }
        }
    }
}