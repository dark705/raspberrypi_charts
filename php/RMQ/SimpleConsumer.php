<?php

namespace RMQ;

class SimpleConsumer extends SimpleExchange
{
    public function listen()
    {
        $this->channel->basic_consume($this->queue, '', false, false, false, false, [$this, 'processMessage']);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function processMessage($msg)
    {
        echo $msg->body;
    }

    protected function sendAck($msg)
    {
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}
