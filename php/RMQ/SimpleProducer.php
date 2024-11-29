<?php

namespace RMQ;

use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class SimpleProducer extends SimpleExchange
{
    public function __construct($config, LoggerInterface $output)
    {
        parent::__construct($config, $output);
        $this->channel->exchange_declare($this->exchange, AMQPExchangeType::FANOUT, false, true, false);
    }

    public function publish($message)
    {
        $AMQPMessage = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->channel->basic_publish($AMQPMessage, $this->exchange);
    }
}
