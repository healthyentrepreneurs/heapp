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
        if ($state == 0) {
            $products = array($_POST);
            $data_pushed = json_encode($products,JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            $data_copy = array(
                'jsonobject' => $data_pushed
            );
            $this->universal_model->updateOnDuplicate('testpost', $data_copy);
            header("Content-type: application/json");
            header('Content-Type: charset=utf-8');
            echo $data_pushed;
            // echo $data_pushed;
        } else {
            echo "joshia xx";
        }
    }
    public function checkstate()
    {
        // $string_ma = '[{"eventname":"\\core\\event\\user_created","amp;component":"core","amp;action":"created","amp;target":"user","amp;objecttable":"user","amp;objectid":6,"amp;crud":"c","amp;edulevel":0,"amp;contextid":28,"amp;contextlevel":30,"amp;contextinstanceid":6,"amp;userid":2,"amp;courseid":0,"amp;relateduserid":6,"amp;anonymous":0,"amp;timecreated":1632738348,"0":1632738348}]';
        $string_ma='[{"eventname":"\\core\\event\\user_deleted","amp;component":"core","amp;action":"deleted","amp;target":"user","amp;objecttable":"user","amp;objectid":5,"amp;crud":"d","amp;edulevel":0,"amp;contextid":27,"amp;contextlevel":30,"amp;contextinstanceid":5,"amp;userid":2,"amp;courseid":0,"amp;relateduserid":5,"amp;anonymous":0,"amp;other":{"username":"pwampa","email":"pwampa@gmail.com","idnumber":"","picture":0,"mnethostid":1},"amp;timecreated":1632738125,"0":1632738125}]';
        // $jsonpa = json_decode($string_ma);
        // $val = html_entity_decode(htmlentities($string_ma, ENT_QUOTES, "UTF-8"));
        $paapa=explode("amp;",$string_ma);
        $one=array_shift($paapa);
        print_array($one);
        // echo $jsonpa;
    }
}
