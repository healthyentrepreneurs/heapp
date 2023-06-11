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

    public function get_chapterto_update($_courseid, $_section_id, $_instance, $_contextid)
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
            $section_of_interest = array();
            foreach ($array_of_courses as $key => $value) {
                // print_array($value);
                if ($value['id'] == $_section_id) {
                    // print_array($value);
                    $section_of_interest = $value;
                    break;
                }
            }
            // print_array($section_of_interest);
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
    }
    #Test Get User Details
    public function get_coursemodule_update($_courseid, $_section, $module_id)
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
        $array_output = array();
        // print_array($array_of_courses);
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $module = $this->get_module($array_of_courses, $_section, $module_id);
            if ($module !== null) {
                header('Content-Type: application/json');
                echo empty_response("course module loaded", 200, $module);
            } else {
                header('Content-Type: application/json');
                echo empty_response("No Existent course and details", 400);
                // echo "No module with id $module_id found in section with id $_section";
            }
        }
    }

    public  function get_section_details($_courseid, $_sectionid)
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
        $data = json_decode($server_output, true);
        foreach ($data as $item) {
            if ($item['id'] == $_sectionid) {
                $sectionData = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'section' => $item['section'],
                    'uservisible' => $item['uservisible'],
                ];

                header('Content-Type: application/json');
                echo json_encode([
                    'exists' => true,
                    'data' => $sectionData
                ]);
                return;
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'exists' => false,
            'data' => null
        ]);
    }
    // for files --> intrance into file syncs | synce in firestore, then download zip | then sync zip
    public function get_h5p_byh5pcontext($_courseid)
    {
        $functionname = 'mod_h5pactivity_get_h5pactivities_by_courses';
        $data = array(
            'wstoken' => get_admin_token()['token'],
            'wsfunction' => $functionname,
            'courseids[0]' => $_courseid,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $plain_data = json_decode($server_output, true);
        print_array($plain_data);
    }


    public function get_sectionid_by_coursemoduleid($_coursemodule_instance)
    {
        // core_course_get_course_module_by_instance
        // core_course_get_module
        // core_course_get_updates_since

        # code...
        $functionname = 'core_course_get_course_module';
        $data = array(
            'wstoken' => get_admin_token()['token'],
            'wsfunction' => $functionname,
            'cmid' => $_coursemodule_instance,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $plain_data = json_decode($server_output, true);
        if (array_key_exists('exception', $plain_data)) {
            // message
            // header('Content-Type: application/json');
            // echo empty_response("No Existent course and details", 400);
            header('Content-Type: application/json');
            echo json_encode([
                'exists' => false,
                'data' => 'No Data found.'
            ]);
            return;
        } else {
            if (isset($plain_data['cm'])) {
                $processed_data = [
                    'section' => $plain_data['cm']['section'],
                    'name' => $plain_data['cm']['name']
                ];

                header('Content-Type: application/json');
                echo json_encode([
                    'exists' => true,
                    'data' => $processed_data
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'exists' => false,
                    'data' => 'No Data found.'
                ]);
            }
        }
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

    function get_module($array_object, $section_id, $module_id)
    {
        foreach ($array_object as $section) {
            if ($section['id'] == $section_id) {
                foreach ($section['modules'] as $module) {
                    if ($module['id'] == $module_id) {
                        return $module;
                    }
                }
            }
        }
        return null; // Return null if no matching module is found
    }
}
