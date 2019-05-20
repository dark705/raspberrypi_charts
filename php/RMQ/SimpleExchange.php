<?php

namespace RMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class SimpleExchange
{
    protected $connection;
    protected $channel;
    protected $queue;
    protected $debug;
    protected $stdout;

    public function __construct($config, $stdout = false, $debug = false)
    {
        $this->debug = $debug;
        $this->stdout = $stdout;
        $this->connection = new AMQPStreamConnection($config['ip'], $config['port'], $config['user'], $config['password']);
        $this->channel = $this->connection->channel();
        $this->queue = $config['queue'];
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
