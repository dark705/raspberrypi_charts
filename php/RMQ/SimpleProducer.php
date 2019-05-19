<?php
namespace RMQ;

use PhpAmqpLib\Message\AMQPMessage;

class SimpleProducer extends SimpleExchange
{
    public function publish($message)
    {
        $AMQPMessage = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->channel->basic_publish($AMQPMessage,'', $this->queue);
    }
}
