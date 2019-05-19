<?php
namespace RMQ;

use PDO\MySQL;

class SensorConsumer extends SimpleConsumer
{
    private $pdo;

    public function setPDO(MySQL $pdo)
    {
        $this->pdo = $pdo;
    }

    public function processMessage($msg)
    {
        if ($this->debug) {
            echo 'Incoming message: ' . $msg->body . PHP_EOL;
        }
        $mes = json_decode($msg->body, true);

        switch($mes['sensor']){
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
            default:
                throw new \Exception('Unknown sensor in RMQ message');
        }
        if ($this->debug) {
            echo 'SQL query: ' . $query . PHP_EOL;
        }

        if ($this->pdo->request($query)){
            echo 'Success query' . PHP_EOL;

            if ($this->needAck) {
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                echo 'RMQ ack' . PHP_EOL;
            }
        }
    }
}