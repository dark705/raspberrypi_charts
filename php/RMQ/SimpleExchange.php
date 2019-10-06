<?php

namespace RMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerInterface;

class SimpleExchange
{
    protected $connection;
    protected $channel;
    protected $queue;
    protected $config;
    protected $output;

    public function __construct($config, LoggerInterface $output)
    {
        $this->config     = $config;
        $this->output     = $output;
        $this->connection = new AMQPStreamConnection(
            $config['ip'],
            $config['port'],
            $config['user'],
            $config['password']
        );
        $this->channel    = $this->connection->channel();
        $this->queue      = $config['queue'];
        $this->channel->queue_declare($this->queue, false, true, false, false);
    }

    public function __destruct()
    {
        $this->output->debug('Close RMQ connection');
        $this->channel->close();
        $this->connection->close();
    }
}
