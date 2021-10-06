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
        $domainname = 'https://app.healthyentrepreneurs.nl/login/token.php?username=mega&password=GoatNa123!@%23XCMan&service=addusers';
        $serverurl = $domainname . '/login/token.php?';
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        //print_array($array_of_output);
        return $array_of_output;
    }
    //User Api Moodle
    public function pullfrommod($state = 1)
    {
        // papa Mega1java123!@# papa@gmail.com / papax@gmail.com  papay papata
        // https://app.healthyentrepreneurs.nl/admin/tool/trigger/manage.php
        // testpost
        // jsonobject
        if ($state == 0) {
            $products = array($_POST);
            $data_pushed = json_encode($products, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            // $data_copy = array(
            //     'jsonobject' => $data_pushed
            // );
            // $this->universal_model->updateOnDuplicate('testpost', $data_copy);
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
        $USERURL = "https://app.healthyentrepreneurs.nl/moodleapi/api/getusers/";
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
        // https://app.healthyentrepreneurs.nl/admin/tool/trigger/manage.php
        // testpost
        // jsonobject
        if ($state == 0) {
            $products = array($_POST);
            $data_pushed = json_encode($products, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            $data_copy = array(
                'jsonobject' => $data_pushed
            );
            $this->universal_model->updateOnDuplicate('testpost', $data_copy);
            header("Content-type: application/json");
            header('Content-Type: charset=utf-8');
        } else {
            echo "not event book ";
        }
    }
    public function bookops()
    {
        $BOOKSURL = base_url("user/get_chapters_perbookcourse");
        // "http://localhost/heapp/";
        // $string_ma = '[{"eventname":"\\mod_book\\event\\chapter_updated","amp;component":"mod_book","amp;action":"updated","amp;target":"chapter","amp;objecttable":"book_chapters","amp;objectid":4,"amp;crud":"u","amp;edulevel":1,"amp;contextid":30,"amp;contextlevel":70,"amp;contextinstanceid":2,"amp;userid":2,"amp;courseid":2,"amp;anonymous":0,"amp;timecreated":1633341234,"0":1633341234}]';
        // $string_ma = '[{"eventname":"\\mod_book\\event\\chapter_deleted","amp;component":"mod_book","amp;action":"deleted","amp;target":"chapter","amp;objecttable":"book_chapters","amp;objectid":4,"amp;crud":"d","amp;edulevel":1,"amp;contextid":30,"amp;contextlevel":70,"amp;contextinstanceid":2,"amp;userid":2,"amp;courseid":2,"amp;anonymous":0,"amp;timecreated":1633341250,"0":1633341250}]';
        $string_ma = '[{"eventname":"\\mod_book\\event\\chapter_created","amp;component":"mod_book","amp;action":"created","amp;target":"chapter","amp;objecttable":"book_chapters","amp;objectid":11,"amp;crud":"c","amp;edulevel":1,"amp;contextid":37,"amp;contextlevel":70,"amp;contextinstanceid":9,"amp;userid":2,"amp;courseid":2,"amp;anonymous":0,"amp;timecreated":1633348673,"0":1633348673}]';
        // $string_ma = '[{"eventname":"\\core\\event\\course_module_created","amp;component":"core","amp;action":"created","amp;target":"course_module","amp;objecttable":"course_modules","amp;objectid":9,"amp;crud":"c","amp;edulevel":1,"amp;contextid":37,"amp;contextlevel":70,"amp;contextinstanceid":9,"amp;userid":2,"amp;courseid":2,"amp;anonymous":0,"amp;other":{"modulename":"book","instanceid":8,"name":"Book Njovu"},"amp;timecreated":1633348655,"0":1633348655}]';
        $amparray = explode("amp;", $string_ma);
        $newstring = implode("", $amparray);
        $paapa = explode(",", $newstring);
        // print_array($paapa);
        $event = $paapa[0];
        if (strpos($event, "course_module_created") !== false) {
            $objectid = explode(':', $paapa[5]);
            $userid = explode(':', $paapa[11]);
            $courseid = explode(':', $paapa[12]);
            $bookid = explode(':', $paapa[15]);  //instanceid
            $timecreated = explode(':', $paapa[17]);
            // print_array($paapa);
            echo "books created";
        } else if (strpos($event, "chapter_created") !== false || strpos($event, "chapter_updated") !== false) {
            // echo "Chapter Created";
            $objectid = explode(':', $paapa[5]);
            $userid = explode(':', $paapa[11]);
            $courseid = explode(':', $paapa[12]);
            $timecreated = explode(':', $paapa[14]);
            // get_chapters_perbookcourse
            // 346
            $server_output = curl_request($BOOKSURL, array('courseid' => 20, 'book_id' => 346), "post", array('App-Key: 123456'));
            $user_array = json_decode($server_output, true);
            if (!empty($user_array)) {
                print_array($user_array);
            } else {
                echo "Chapter Created / Chapter Updated";
            }
            // print_array($server_output);

        } else if (strpos($event, "chapter_deleted") !== false) {
            print_array($paapa);
            echo "Chapter Deleted";
        } else {
            echo "Nara Papa";
        }
    }
}
