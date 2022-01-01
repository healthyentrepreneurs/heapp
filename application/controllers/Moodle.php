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
            $serverurl = MOODLEAPP_DOMAIN . '/login/token.php';
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
        $token =  $token = $this->get_admin_token()['token'];
        $functionname = 'core_user_get_users_by_field';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'username',
            'values[0]' => $username

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function get_userdetails_internal_todelete($username = null)
    {
        $token = $this->get_admin_token()['token'];
        $functionname = 'core_user_get_users_by_field';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'username',
            'values[0]' => $username

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function get_admin_token()
    {
        $domainname = MOODLEAPP_DOMAIN.'/login/token.php?username=mega&password=Walah123!@%23CMaw&service=addusers';
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    //User Api Moodle
    public function pullfrommod($state = 1)
    {
        if ($state == 0) {
            $products = array($_POST);
            $data_pushed = json_encode($products, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            // header("Content-type: application/json");
            // header('Content-Type: charset=utf-8');
            $this->checkstate($data_pushed);
        } else {
            echo "not event logged ";
        }
    }
    //User Api Moodle
    public function checkstate($string_ma)
    {
        $USERURL = MOODLEAPP_DOMAIN . "/moodleapi/api/getusers/";
        // $string_ma = '[{"eventname":"\\core\\event\\user_created","amp;component":"core","amp;action":"created","amp;target":"user","amp;objecttable":"user","amp;objectid":6,"amp;crud":"c","amp;edulevel":0,"amp;contextid":28,"amp;contextlevel":30,"amp;contextinstanceid":6,"amp;userid":2,"amp;courseid":0,"amp;relateduserid":6,"amp;anonymous":0,"amp;timecreated":1632738348,"0":1632738348}]';
        // $string_ma = '[{"eventname":"\\core\\event\\user_deleted","amp;component":"core","amp;action":"deleted","amp;target":"user","amp;objecttable":"user","amp;objectid":5,"amp;crud":"d","amp;edulevel":0,"amp;contextid":27,"amp;contextlevel":30,"amp;contextinstanceid":5,"amp;userid":2,"amp;courseid":0,"amp;relateduserid":5,"amp;anonymous":0,"amp;other":{"username":"pwampa","email":"pwampa@gmail.com","idnumber":"","picture":0,"mnethostid":1},"amp;timecreated":1632738125,"0":1632738125}]';
        // $string_ma = '[{"eventname":"\\core\\event\\user_updated","amp;component":"core","amp;action":"updated","amp;target":"user","amp;objecttable":"user","amp;objectid":3,"amp;crud":"u","amp;edulevel":0,"amp;contextid":25,"amp;contextlevel":30,"amp;contextinstanceid":3,"amp;userid":2,"amp;courseid":0,"amp;relateduserid":3,"amp;anonymous":0,"amp;timecreated":1633332727,"0":1633332727}]';
        $amparray = explode("amp;", $string_ma);
        $newstring = implode("", $amparray);
        $paapa = explode(",", $newstring);
        $event = $paapa[0];
        //user_created instance
        if (strpos($event, "user_created") !== false || strpos($event, "user_deleted") !== false || strpos($event, "user_updated") !== false) {
            // echo "User Created";
            $id_objectid = $paapa[5];
            $object_id_value = explode(':', $id_objectid);
            $user_curl = $USERURL . $object_id_value[1];
            $server_output = curl_request($user_curl, array(), "get", array('App-Key: 123456'));
            $user_array = json_decode($server_output, true);
            if (!empty($user_array)) {
                $this->universal_model->updateOnDuplicate('mdl_user', $user_array);
                header("Content-type: application/json");
                header('Content-Type: charset=utf-8');
                echo json_encode(array('id' => $user_array['id'], 'added' => 'user added'));
            } else {
                header("Content-type: application/json");
                header('Content-Type: charset=utf-8');
                echo json_encode(array('id' => $object_id_value[1], 'added' => 'user not added'));
            }
        } else {
            header("Content-type: application/json");
            header('Content-Type: charset=utf-8');
            echo json_encode(array('id' => "none", 'added' => 'no user action done user'));
        }
    }

    public function pullbook($state = 1)
    {
        // papa Mega1java123!@# papa@gmail.com / papax@gmail.com  papay papata
        // testpost
        // jsonobject
        if ($state == 0) {
            $products = array($_POST);
            $data_pushed = json_encode($products, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            $data_copy = array(
                'jsonobject' => $data_pushed
            );
            // curl_request_json("https://he-test-server.uc.r.appspot.com/moodlevent", $data_pushed);
            $this->universal_model->updateOnDuplicate('testpost', $data_copy);
            header("Content-type: application/json");
            header('Content-Type: charset=utf-8');
            echo "not book ";
        } else {
            echo "not event book ";
        }
    }
}
