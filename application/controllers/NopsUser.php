<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");
require_once FCPATH . 'vendor/autoload.php';
class NopsUser extends CI_Controller
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
        echo "<h1>Api Users Backup Api .....</h1>";
    }
    ##End Courses

    public function get_details_percourse($_courseid, $token, $show = 1)
    {
        $functionname = 'core_course_get_contents';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'courseid' => $_courseid
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            $result = array();
            if ($show == 1) {
                echo empty_response("No Existent course and details", 400);
            } else {
                return $result;
            }
        } else {
            $generalicons = array();
            $contenthtml = array();
            //Above uni arrays
            $formatedCoursecontent = array();
            foreach ($array_of_courses as $keya => $value_from_data) {
                $formatedModules = array();
                foreach ($value_from_data['modules'] as $keyb => $modules_values) {
                    if ($modules_values['modname'] == "book") {
                        $arrayicons = array(
                            'type' => 'book',
                            'value' => $modules_values['modicon']
                        );
                        $this->addifempty($generalicons, $arrayicons);
                        $modules_values['modicon'] = $this->onlineUrlReturner($_courseid, $modules_values['modicon'], $modules_values['id'], $modules_values['modname']);
                        //Format Content Modules Wise
                        $formatedModuleContents = array();
                        foreach ($modules_values['contents'] as $key => $deepestvalue) {

                            if ($deepestvalue['filename'] == "index.html" && $deepestvalue['type'] == "file") {
                                $contenturl = $deepestvalue['fileurl'] . '?token=' . $token;
                                $arrayncontent = array('value' => $contenturl, 'type' => 'book', 'courseid' => $_courseid);
                                $this->addifempty($contenthtml, $arrayncontent);
                                // $deepestvalue['fileurl'] = file_get_contents($contenturl);
                                // For HTML DOWNLOAD
                                // v_two.Fileurl = heu.Gethtmlstring(_token_url)
                            }
                            array_push($formatedModuleContents, $deepestvalue);
                        }
                        $modules_values['contents'] = $formatedModuleContents;
                        array_push($formatedModules, $modules_values);
                    } elseif ($modules_values['modname'] == "quiz") {
                        array_push($formatedModules, $modules_values);
                    }
                }
                $value_from_data['modules'] = $formatedModules;
                array_push($formatedCoursecontent, $value_from_data);
            }
            // print_array($contenthtml);
            header('Content-Type: application/json');
            echo empty_response("course sections loaded", 200, $formatedCoursecontent);
        }
    }
    #Test Get User Details

    //HELPER FUNCTIONS
    public function addifempty(&$array, &$stringurl)
    {
        if (in_array($stringurl, $array)) {
        } else {
            array_push($array, $stringurl);
        }
    }

    public function onlineUrlReturner($course_id, $urlfile, $contentid, $content_type)
    {
        // $book_id = $_filter_modules['id'];
        // $type_book = $_filter_modules['modname'];
        $value_check = $this->universal_model->select_bytwo_limit('bookid', $contentid, 'couseid', $course_id, 'type', $content_type);
        if (!empty($value_check)) {
            $url_icon = array_shift($value_check)['image_big'];
            return  base_url('uploadicons/' . $url_icon);
        }
        return $urlfile;
    }
    public function formatJsonForLocalNoneRef($jsonData, $originalurl, $localurl, $tokenurl)
    {
        //Double Quotes Are Not Escaped
        $originalurljson = str_replace('"', '', json_encode($originalurl));
        $localurljson = str_replace('"', '', json_encode($localurl));
        // echo $localurljson;
        $jsonData = str_replace($originalurljson, $localurljson, $jsonData);
        if ($tokenurl != 'none') {
            $jsonData = str_replace($tokenurl, "", $jsonData);
        }
        return $jsonData;
    }
}
