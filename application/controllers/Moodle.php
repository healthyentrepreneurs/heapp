<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Moodle extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // $this->load->model('moodle_model', '', TRUE);
    }
    public function index($var = null)
    {
        // echo $this->hash_internal_user_password("Thijs123!@#");
    }
    public function login($var = null)
    {
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
}
