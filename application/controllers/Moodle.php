<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Moodle extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // $this->load->library('curl');
        // $this->load->model('moodle_model', '', TRUE);
        //Downloadable Moodle
    }
    public function index($var = null)
    {
        echo "<h1>Moodle Api Intergration</h1>";
        // echo $this->hash_internal_user_password("Thijs123!@#");
    }
    public function login($var = null)
    {
        // header('Access-Control-Allow-Origin: *');
        // $_POST['username'] = "admin";
        // $_POST['password'] = "Thijs123!@#";
        // $_POST['username'] = "mega";
        // $_POST['password'] = "Mega1java123!@#";
        // $_POST['username'] = "7290";
        // $_POST['password'] = "123456";
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = str_replace(' ', '', $this->input->post('username'));
            $password = str_replace('#', '%23', $this->input->post('password'));
            // $password = $this->input->post('password');
            $domainname = 'https://app.healthyentrepreneurs.nl';
            $serverurl = $domainname . '/login/token.php';
            $data = array(
                'username' => $username,
                'password' => $password,
                'service' => 'moodle_mobile_app'
            );
            $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            if (array_key_exists('exception', $array_of_output)) {
                echo empty_response(strip_tags($array_of_output['message']));
            } else {
                if (array_key_exists('errorcode', $array_of_output)) {
                    echo empty_response(strip_tags($array_of_output['error']));
                } else {
                    $details_user = $this->get_userdetails_internal($username);
                    $token_details = array_merge($array_of_output, $details_user[0]);
                    $data = array(
                        'id_id' => $details_user[0]['id'],
                        'username' => $username,
                        'password' => $this->input->post('password'),
                    );
                    // print_array($data);
                    $value_check = $this->universal_model->updateOnDuplicate('user', $data);
                    // print_array($token_details);
                    echo empty_response("successfully logged in", 200, $token_details);
                }
            }
        } else {
            echo empty_response("Credentials Are Required");
        }
    }
    public function get_userdetails_internal($username = null)
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token =  $token = $this->get_admin_token()['token'];
        $functionname = 'core_user_get_users_by_field';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'username',
            'values[0]' => $username

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function get_userdetails_internal_todelete($username = null)
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = $this->get_admin_token()['token'];
        $functionname = 'core_user_get_users_by_field';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'username',
            'values[0]' => $username

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function get_admin_token()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl/login/token.php?username=mega&password=PapaWemba123!@%23X&service=addusers';
        $serverurl = $domainname . '/login/token.php?';
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        //print_array($array_of_output);
        return $array_of_output;
    }

    public function pullfrommod($state = 1)
    {
        // papa Mega1java123!@# papa@gmail.com / papax@gmail.com  papay papata
        // https://app.healthyentrepreneurs.nl/admin/tool/trigger/manage.php
        // testpost
        // jsonobject
        // $entityBody = file_get_contents('php://input');
        $data_pushed = json_encode($_POST);
        if ($state == 0) {
            $data_copy = array(
                'jsonobject' => $data_pushed
            );
            $this->universal_model->updateOnDuplicate('testpost', $data_copy);
            echo $data_pushed;
        } else {
            echo "joshia";
        }
    }
}

// Content-Type: application/json Accept: application/json

// user[0]={user_id}&user[1]={user_username}&user[2]={user_firstname}&user[3]={user_lastname}

// action=course_completion&course[0]={course_id}&course[0]={course_shortname}&user[0]={user_id}&user[1]={user_username}&user[2]={user_firstname}&user[3]={user_lastname}
