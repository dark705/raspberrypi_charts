<?php

namespace RMQ;

class SimpleConsumer extends SimpleExchange
{
    public function listen()
    {
        $this->output->info('Start RMQ Consumer');
        $this->channel->basic_consume($this->queue, '', false, false, false, false, [$this, 'processMessage']);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function processMessage($msg)
    {
        $this->output->info('Process RMQ Message');
        $this->output->debug('RMQ Message:', ['body' => $msg->body]);
    }

    protected function sendAck($msg)
    {
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        $this->output->debug('Make RMQ ack');
    }
}
