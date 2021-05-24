<?php
ini_set('max_execution_time', '3000');
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Red extends CI_Controller
{
    // const CONNECTION = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
    }
    public function send()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');
        echo " [x] Sent 'Hello World!'\n";
        $channel->close();
        $connection->close();
    }
    public function receive()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        while ($channel->is_open()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}
