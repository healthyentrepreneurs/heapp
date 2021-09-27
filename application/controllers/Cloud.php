<!-- https://cloud.google.com/php/grpc#php-implementation -->
<!-- https://cloud.google.com/php/getting-started/background-processing -->

<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");

class Cloud extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        // $this->load->library('curl');
        // $this->load->model('moodle_model', '', TRUE);
    }
    public function index($var = null)
    {
    }
    public function basx()
    {
        echo base_url();
        //  
        // Public/usr/bin
        // export PATH=/Applications/MAMP/bin/php/php7.4.21/bin:$PATH   
        // export PATH=/usr/local/Cellar/php71/7.1.14_25/bin:$PATH
    }
}
// extension=grpc.so
?>