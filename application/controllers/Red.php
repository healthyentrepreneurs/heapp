<?php
ini_set('max_execution_time', '3000');
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once FCPATH . 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
class Red extends CI_Controller
{
    public function testemit()
    {
       $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
       $channel = $connection->channel();
    }
}
