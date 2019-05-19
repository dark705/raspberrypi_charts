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

    protected function processMessage($msg)
    {
        echo $msg->body;
    }
}
