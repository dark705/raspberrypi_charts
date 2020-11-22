<?php

namespace RMQ;

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
            case 'pzem004t.v3':
                $mes['date'] = str_replace('+03:00', '', $mes['date']); //goLang datetime
                $query       = sprintf("INSERT INTO `pzem004t` (`datetime`, `voltage`, `current`, `active`, `energy`) VALUES ('%s', '%s', '%s', '%s', '%s');",
                                       $mes['date'], $mes['data']['voltage'], $mes['data']['current'], $mes['data']['active'], $mes['data']['energy']);
                break;
            case 'dht22':
                $mes['date'] = str_replace('+03:00', '', $mes['date']); //goLang datetime
                $query       = sprintf("INSERT INTO `dht22` (`datetime`,`temperature`, `humidity`) VALUES ('%s', '%s', '%s');",
                                       $mes['date'], $mes['data']['temperature'], $mes['data']['humidity']);
                break;
            case 'ds18b20':
                $query = sprintf("INSERT INTO `ds18b20` (`datetime`, `serial`, `temperature`) VALUES ('%s', '%s', '%s');",
                                 $mes['date'], $mes['data']['serial'], $mes['data']['temperature']);
                break;
            case 'bmp280':
                $mes['date'] = str_replace('+03:00', '', $mes['date']); //goLang datetime
                $query       = sprintf("INSERT INTO `bmp280` (`datetime`, `pressure`, `temperature`) VALUES ('%s', '%s', '%s');",
                                       $mes['date'], $mes['data']['pressure'], $mes['data']['temperature']);
                break;
            default:
                $this->output->warning('Unknown sensor in RMQ message', ['rmqMessage' => $mes] );
                if ($this->config['ack']) {
                    $this->sendAck($msg);
                }
        }
        $this->output->debug('SQL query: ', ['query' => $query ?? null]);

        if (isset($query)){
            if ($this->pdo->request($query)) {
                $this->output->debug('Success MySQL insert');
                if ($this->config['ack']) {
                    $this->sendAck($msg);
                }
            }
        }
    }
}