<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
        echo "<h1>Api Users Api .....</h1>";
    }
    public function test()
    {
    }
    public function get_list_courses()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = 'f84bf33b56e86a4664284d8a3dfb5280';
        $functionname = 'core_course_get_courses';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            return array();
        } else {
            return $array_of_courses;
        }
    }
    public function set_newuser()
    {
        if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email'])) {
            $firstname     = $this->input->post('firstname');
            $lastname     = $this->input->post('lastname');
            $email           = $this->input->post('email');
            $city         = "Kampala";
            $country      = "UG";
            $description = "Auto generated description please edit";
            $user1 = new stdClass();
            $user1->username     = mb_strtolower($firstname) . '_' . mb_strtolower($lastname);
            $user1->password     = "Newuser123!";
            $user1->firstname     = $firstname;
            $user1->lastname     = $lastname;
            $user1->email         = $email;
            $user1->auth         = 'manual';
            // $user1->idnumber     = 'numberID';
            $user1->lang         = 'en';
            $user1->city         = $city;
            $user1->country     = $country;
            $user1->description = $description;
            $users = array($user1);
            $array = json_decode(json_encode($users), true);
            // $params = array();
            //Call
            $domainname = 'https://app.healthyentrepreneurs.nl';
            $token = 'f84bf33b56e86a4664284d8a3dfb5280';
            $functionname = 'core_user_create_users';
            $serverurl = $domainname . '/webservice/rest/server.php';
            $data = array(
                'wstoken' => $token,
                'wsfunction' => $functionname,
                'moodlewsrestformat' => 'json',
                'users' => $array
            );
            $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            // print_array($array_of_output);
            if (array_key_exists('exception', $array_of_output)) {
                // message
                echo empty_response(strip_tags($array_of_output['message']));
            } else {
                // array_map(function ($v1, $v2) {
                //     echo $v1['id'] . " " . $v2['id'];
                // }, $array_of_output, $this->get_list_courses());
                foreach ($this->get_list_courses() as $key => $course) {
                    foreach ($array_of_output as $key => $user) {
                        $this->enrol($user['id'], $course['id']);
                    }
                }
                echo empty_response("New User Successfully Created", 200, $array_of_output);
            }
        } else {
            echo empty_response("Credentials Are Required");
        }
    }
    function enrol($user_id, $course_id)
    {
        $role_id = 5; //assign role to be Student
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = 'f84bf33b56e86a4664284d8a3dfb5280';
        $functionname = 'enrol_manual_enrol_users';
        $enrolment = array('roleid' => $role_id, 'userid' => $user_id, 'courseid' => $course_id);
        $enrolments = array($enrolment);
        // $params = array('enrolments' => $enrolments);
        $array = json_decode(json_encode($enrolments), true);
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'enrolments' => $array
        );
        curl_request($serverurl, $data, "post", array('App-Key: 123456'));
    }
}
