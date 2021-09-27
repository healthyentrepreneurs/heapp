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
            $data_pushed = json_encode($products,  JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
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
        $string_ma = '[{"eventname":"\\core\\event\\user_created","amp;component":"core","amp;action":"created","amp;target":"user","amp;objecttable":"user","amp;objectid":6,"amp;crud":"c","amp;edulevel":0,"amp;contextid":28,"amp;contextlevel":30,"amp;contextinstanceid":6,"amp;userid":2,"amp;courseid":0,"amp;relateduserid":6,"amp;anonymous":0,"amp;timecreated":1632738348,"0":1632738348}]';
        // $string_ma = '[{"id":2,"fullname":"Education and Prevention","categoryid":2,"source":"moodle","summary_custome":"In Luganda .. ","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/user\/get_details_percourse\/2\/7afb8d11b501db8a6f66f0f34886d1ca","image_url_small":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/30\/course\/overviewfiles\/education.png?token=7afb8d11b501db8a6f66f0f34886d1ca","image_url":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/30\/course\/overviewfiles\/education.png?token=7afb8d11b501db8a6f66f0f34886d1ca"},{"id":16,"fullname":"Education and Prevention","categoryid":5,"source":"moodle","summary_custome":"In Runyankole .. ","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/user\/get_details_percourse\/16\/7afb8d11b501db8a6f66f0f34886d1ca","image_url_small":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/566\/course\/overviewfiles\/education.png?token=7afb8d11b501db8a6f66f0f34886d1ca","image_url":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/566\/course\/overviewfiles\/education.png?token=7afb8d11b501db8a6f66f0f34886d1ca"},{"id":20,"fullname":"Hypertension\/Diabetes Screening - KE","categoryid":9,"source":"moodle","summary_custome":"Instructions and error codes. .. ","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/user\/get_details_percourse\/20\/7afb8d11b501db8a6f66f0f34886d1ca","image_url_small":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/2335\/course\/overviewfiles\/diabetes.png?token=7afb8d11b501db8a6f66f0f34886d1ca","image_url":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/2335\/course\/overviewfiles\/diabetes.png?token=7afb8d11b501db8a6f66f0f34886d1ca"},{"id":4,"fullname":"Product Information","categoryid":8,"source":"moodle","summary_custome":"Information how to use the products which HE provides. .. ","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/user\/get_details_percourse\/4\/7afb8d11b501db8a6f66f0f34886d1ca","image_url_small":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/90\/course\/overviewfiles\/products.png?token=7afb8d11b501db8a6f66f0f34886d1ca","image_url":"https:\/\/app.healthyentrepreneurs.nl\/webservice\/pluginfile.php\/90\/course\/overviewfiles\/products.png?token=7afb8d11b501db8a6f66f0f34886d1ca"},{"id":41,"fullname":"Hypertension\/Diabetes Screening - KE","categoryid":2,"source":"originalm","summary_custome":"Registration of Vitals","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/41","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_piclLT.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_piclLT.png"},{"id":10,"fullname":"Workflow: Family Planning","categoryid":2,"source":"originalm","summary_custome":"To help choose the right contraceptive","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/10","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_pic2Zq.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_pic2Zq.png"},{"id":7,"fullname":"Prevention Checklists","categoryid":2,"source":"originalm","summary_custome":"To do lists for various health topics","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/7","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_picaJ4.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_picaJ4.png"},{"id":1,"fullname":"Workflow: ICCM children under 5 (KE)","categoryid":2,"source":"originalm","summary_custome":"Workflow for ICCM cases. ","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/1","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_picK6h.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_picK6h.png"},{"id":10,"fullname":"Workflow: Family Planning","categoryid":2,"source":"originalm","summary_custome":"To help choose the right contraceptive","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/10","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_pic2Zq.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_pic2Zq.png"},{"id":7,"fullname":"Prevention Checklists","categoryid":2,"source":"originalm","summary_custome":"To do lists for various health topics","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/7","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_picaJ4.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_picaJ4.png"},{"id":2,"fullname":"Workflow: Eye, Ear or Skin problems and PUD","categoryid":2,"source":"originalm","summary_custome":"Use this form to send data to the nurses and doctors of HE.","next_link":"https:\/\/helper.healthyentrepreneurs.nl\/survey\/getnexlink\/2","image_url_small":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/50_user_profile_picuKP.png","image_url":"https:\/\/helper.healthyentrepreneurs.nl\/uploadscustome\/600_user_profile_picuKP.png"}]';
        // $jsonpa = json_decode($string_ma);
        $val = html_entity_decode(htmlentities($string_ma, ENT_QUOTES, "UTF-8"));
        $paapa=json_decode($val);
        print_array($paapa);
        // echo $jsonpa;
    }
}
