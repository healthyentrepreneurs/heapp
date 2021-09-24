<!-- https://cloud.google.com/php/grpc#php-implementation -->
<!-- https://cloud.google.com/php/getting-started/background-processing -->

<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");

use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Transaction;

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
        $firestore = new FirestoreClient();
        echo "<h1>Api Cloud .....</h1>";
        
    }
}


?>