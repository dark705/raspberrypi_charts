<?php

namespace RMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class SimpleExchange
{
    protected $connection;
    protected $channel;
    protected $queue;
    protected $debug;
    protected $needAck;

    public function __construct($config, $debug = false)
    {
        $this->debug = $debug;
        $this->connection = new AMQPStreamConnection($config['ip'], $config['port'], $config['user'], $config['password']);
        $this->channel = $this->connection->channel();
        $this->queue = $config['queue'];
        $this->needAck = $config['ack'];
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
