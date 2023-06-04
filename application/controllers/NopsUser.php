<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");
require_once FCPATH . 'vendor/autoload.php';
class Nopsuser extends CI_Controller
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
                    } elseif ($modules_values['modname'] == "h5pactivity") {
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

    public function get_chapterto_update($_courseid, $_section, $_instance, $_contextid)
    {
        $token = get_admin_token()['token'];
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
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $generalicons = array();
            $content_needs = array();
            $section_of_interest = $array_of_courses[$_section];
            if (!empty($section_of_interest)) {
                $books_to_search = $section_of_interest['modules'];
                // print_array($books_to_search);
                foreach ($books_to_search as $key => $value) {
                    if ($value['instance'] == $_instance && $value['contextid'] == $_contextid) {
                        // $content_needs = $value['contents'];
                        $arrayicons = array(
                            'type' => 'book',
                            'value' => $value['modicon']
                        );
                        $this->addifempty($generalicons, $arrayicons);
                        $value['modicon'] = $this->onlineUrlReturner($_courseid, $value['modicon'], $value['id'], $value['modname']);
                        $content_needs = $value;
                        break;
                    }
                }
            }
            //Above uni arrays

        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $content_needs);
        // print_array($content_needs);
    }
    #Test Get User Details
    public function get_coursemodule_update($_courseid, $_section)
    {
        $token = get_admin_token()['token'];
        $functionname = 'core_course_get_contents';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'courseid' => $_courseid
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        $array_output = array(); // We have to keep the format consistent, Array format
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $generalicons = array();
            $section_of_interest = $array_of_courses[$_section];
            array_push($array_output, $section_of_interest);
            // print_array($array_output);

        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $array_output);
        // print_array($content_needs);
    }
    public  function get_sectionid_bycontextid($_contextid)
    {
        // core_course_get_course_content_items NOT HELPFULL
        // core_course_get_course_module **
        // core_course_get_course_module_by_instance
        // core_course_get_module

        $token = get_admin_token()['token'];
        $functionname = 'core_course_get_course_module';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'cmid' => $_contextid
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        $array_output = array();
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $array_output = $array_of_courses['cm'];
            // An array with the keys you want to keep
            $keys_to_keep = array_flip(['id', 'course', 'module', 'name', 'modname', 'instance', 'section', 'sectionnum']);

            // Use array_intersect_key to keep only the desired keys
            $filtered_array = array_intersect_key($array_output, $keys_to_keep);

            // Now $filtered_array only contains the keys you specified

        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $filtered_array);
        // print_array($array_output);
        // 
    }
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
