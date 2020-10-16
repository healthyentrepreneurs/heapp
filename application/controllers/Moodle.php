<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Moodle extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // $this->load->library('curl');
        // $this->load->model('moodle_model', '', TRUE);
    }
    public function index($var = null)
    {
        // echo $this->hash_internal_user_password("Thijs123!@#");
    }
    public function login_old_v1($var = null)
    {
        // $_POST['email'] = "megasega91@gmail.com";
        // $_POST['password'] = "Mega1java123!@#";
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $datan = $this->universal_model->selectz_pigination('mdl_user', "email", $email, 1, 0);
            if (empty($datan)) {
                echo empty_response("User Does not Exist");
            } else {
                $datan_password = $datan[0]['password'];
                $status = password_verify($password, $datan_password);
                if ($status) {
                    unset_post($datan[0], 'password');
                    $response = array(
                        'code' => 200,
                        'msg' => "successfully logged in",
                        'data' => $datan[0]
                    );
                    echo json_encode($response);
                } else {
                    echo empty_response("Wrong Credentials");
                }
            }
        } else {
            echo empty_response("Credentials Are Required");
        }
    }
    public function test()
    {

        $server_output = curl_request('http://localhost/heapp/login', array('email' => 'megasega91@gmail.com', 'password' => 'Mega1java123!@#'), "post", array('App-Key: 123456'));
        print_array($server_output);
    }
    public function login($var = null)
    {
        // $_POST['username'] = "testuser_testuser";
        // $_POST['password'] = "Newuser123!";
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $domainname = 'https://app.healthyentrepreneurs.nl';
            $serverurl = $domainname . '/login/token.php';
            $data = array(
                'username' => $username,
                'password' => $password,
                'service' => 'moodle_mobile_app'
            );
            $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            echo empty_response("New User Successfully Created", 200, $array_of_output);
        } else {
            echo empty_response("Credentials Are Required");
        }
    }
}
