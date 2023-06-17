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
        // print_array($server_output);
        $array_of_courses = json_decode($server_output, true);
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            $result = array();
            if ($show == 1) {
                echo empty_response("Credentials Are Required");
            } else {
                return $result;
            }
        } else {
            $array_merger = array();
            foreach ($array_of_courses as $key => $_submodules) {
                $_submodules['summary'] = strip_tags($_submodules['summary']);
                // array_push($array_merger, $_submodules);
                $course_id = $_submodules['id'];
                $array_modules = array();
                foreach ($_submodules['modules'] as $key => $_filter_modules) {
                    $book_id = $_filter_modules['id'];
                    $type_book = $_filter_modules['modname'];
                    $value_check = $this->universal_model->select_bytwo_limit('bookid', $book_id, 'couseid', $course_id, 'type', $type_book);
                    // $value_check = $this->universal_model->selectzx('*', 'icon_table', 'original_one', $_filter_modules['modicon'], 'name', $_filter_modules['name'], 'type', $_filter_modules['modname']);
                    if (!empty($value_check)) {
                        $url_icon = array_shift($value_check)['image_big'];
                        $_filter_modules['modicon'] = base_url('uploadicons/' . $url_icon);
                        // print_array($_filter_modules);
                    }
                    #Hey
                    $new_content = array();
                    if ($_filter_modules['modname'] == "book") {
                        $contents = $_filter_modules['contents'];
                        //Array Search Manipulation
                        $contents_dub = $contents;
                        unset_post($contents_dub, 0);
                        // End Array Search Manipulation
                        // unset_post($filter_modules, 'contents');
                        unset_post($_filter_modules, 'contents');
                        foreach ($contents as $keyn => $content_value) {
                            // $content_value
                            if ($content_value['type'] == "content") {
                                $content_n = $content_value['content'];
                                unset_post($content_value, 'content');
                                $content_n1 = json_decode($content_n, true);
                                $cleaner_content = array();
                                foreach ($content_n1 as $key => $value_n) {
                                    $value_search = explode('/', $value_n['href']);
                                    foreach ($contents_dub as $keyn => $value_check) {
                                        if (strpos($value_check['filepath'], $value_search[0]) !== false && strpos($value_check['filename'], $value_search[1]) !== false) {
                                            $value_n['filefullpath'] = $value_check['fileurl'] . "?token=" . $token;
                                            $value_n['chapter_id'] = str_replace("/index.html", "", $value_n['href']);
                                            array_push($cleaner_content, $value_n);
                                            break;
                                        }
                                    }
                                }
                                $content_value['content'] = json_encode($cleaner_content);
                                // array_push($new_content, $content_value);
                            }
                            //Remove Fake Characters
                            $newfileurl = $content_value['fileurl'];
                            $file_name_chap_nchange = str_replace("%28", "(", $newfileurl);
                            $file_name_chap_nchange = str_replace("%29", ")", $file_name_chap_nchange);
                            $file_name_chap_nchange = str_replace("%20", " ", $file_name_chap_nchange);
                            $content_value['fileurl'] = $file_name_chap_nchange;
                            // if ($content_value['type'] == "file" && $content_value['filename'] != "index.html") {
                            //     $content_file_token = $content_value['fileurl'] . "?token=" . $token;
                            //     $content_value['fileurl'] = $content_file_token;
                            // }
                            //End 
                            array_push($new_content, $content_value);
                        }
                        $_filter_modules['contents'] = $new_content;
                        array_push($array_modules, $_filter_modules);
                    } else if ($_filter_modules['modname'] == "h5pactivity") {
                        $_filter_modules['contentsinfo'] = json_encode($arrayName = array('' => '',));
                        $_filter_modules['contents'] = array();
                        array_push($array_modules, $_filter_modules);
                    }
                    // print_array($value_check);
                }
                $_submodules['modules'] = $array_modules;
                array_push($array_merger, $_submodules);
            }
            // print_array($array_merger);
            // return $array_of_courses;
            // Hello Sunshine 
            if ($show == 1) {
                header('Content-Type: application/json');
                echo empty_response("course sections loaded", 200, $array_merger);
                // print_array($array_merger);
            } else {
                return $array_merger;
            }
        }
    }

    public function get_chapterto_update($_courseid, $_section_id, $_instance, $_contextid, $_filepath)
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
                if ($value['id'] == $_section_id) {
                    $section_of_interest = $value;
                    break;
                }
            }
            if (!empty($section_of_interest)) {
                $books_to_search = $section_of_interest['modules'];
                foreach ($books_to_search as $key => $value) {
                    if ($value['instance'] == $_instance && $value['contextid'] == $_contextid) {
                        // $content_needs = $value['contents'];
                        $arrayicons = array(
                            'type' => 'book',
                            'value' => $value['modicon']
                        );
                        $this->addifempty($generalicons, $arrayicons);
                        $value['modicon'] = $this->onlineUrlReturner($_courseid, $value['modicon'], $value['id'], $value['modname']);
                        $contents = $value['contents'];
                        $filtered_contents = array();
                        // Include the content with filepath as "/"
                        foreach ($contents as $content) {
                            if ($content['filepath'] == '/') {
                                $filtered_contents[] = $content;
                                break;
                            }
                        }
                        // Include the content with specified filepath
                        foreach ($contents as $content) {
                            if ($content['filepath'] == "/{$_filepath}/") {
                                $filtered_contents[] = $content;
                            }
                        }
                        // Update the 'contents' in the value
                        $value['contents'] = $filtered_contents;
                        $content_needs = $value;
                        break;
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $content_needs);
    }

    public function get_chapterafter_delete($_courseid, $_section_id, $_contextid)
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
                $filtered_contents = []; // Initialize an empty array to hold the filtered contents

                foreach ($books_to_search as $key => $value) {
                    if ($value['contextid'] == $_contextid) {
                        $arrayicons = array(
                            'type' => 'book',
                            'value' => $value['modicon']
                        );
                        $this->addifempty($generalicons, $arrayicons);
                        $value['modicon'] = $this->onlineUrlReturner($_courseid, $value['modicon'], $value['id'], $value['modname']);
                        $content_needs = $value;
                        $contents = $content_needs['contents'];

                        // Check each content instance in the contents array
                        foreach ($contents as $content) {
                            if ($content['filepath'] == "/" && $content['filename'] == "structure") {
                                // If the content instance meets the condition, add it to the filtered contents array
                                $filtered_contents[] = $content;
                                break; // Stop searching after finding the first matching content instance
                            }
                        }
                        // Replace the 'contents' value of the 'content_needs' array with the 'filtered_contents' array
                        $content_needs['contents'] = $filtered_contents;
                        break;
                    }
                }
            }

            //Above uni arrays

        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $content_needs);
    }

    // public function get_coursemodule_update($_courseid, $_section, $module_id)
    // {
    //     $token = get_admin_token()['token'];
    //     $functionname = 'core_course_get_contents';
    //     $data = array(
    //         'wstoken' => $token,
    //         'wsfunction' => $functionname,
    //         'moodlewsrestformat' => 'json',
    //         'courseid' => $_courseid
    //     );
    //     $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
    //     $array_of_courses = json_decode($server_output, true);
    //     $array_output = array();
    //     // print_array($array_of_courses);
    //     if (array_key_exists('exception', $array_of_courses)) {
    //         // message
    //         header('Content-Type: application/json');
    //         echo empty_response("No Existent course and details", 400);
    //         return;
    //     } else {
    //         $module = $this->get_module($array_of_courses, $_section, $module_id);
    //         if ($module !== null) {
    //             header('Content-Type: application/json');
    //             echo empty_response("course module loaded", 200, $module);
    //         } else {
    //             header('Content-Type: application/json');
    //             echo empty_response("No Existent course and details", 400);
    //             // echo "No module with id $module_id found in section with id $_section";
    //         }
    //     }
    // }
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
        if (array_key_exists('exception', $array_of_courses)) {
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $module = $this->get_module($array_of_courses, $_section, $module_id);
            if ($module !== null) {
                // Fetch deployedfile details and add it to the module data.
                $deployedfile = $this->get_h5p_byh5pcontext($_courseid, $module['id'], $module['instance']);
                if (!empty($deployedfile)) {
                    $module['deployedfile'] = $deployedfile;
                }
                header('Content-Type: application/json');
                echo empty_response("course module loaded", 200, $module);
            } else {
                header('Content-Type: application/json');
                echo empty_response("No Existent course and details", 400);
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
    // public function get_h5p_byh5pcontext($_courseid)
    // {
    //     $functionname = 'mod_h5pactivity_get_h5pactivities_by_courses';
    //     $data = array(
    //         'wstoken' => get_admin_token()['token'],
    //         'wsfunction' => $functionname,
    //         'courseids[0]' => $_courseid,
    //         'moodlewsrestformat' => 'json'

    //     );
    //     $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));

    //     // header('Content-Type: application/json');
    //     // echo $server_output;
    //     $plain_data = json_decode($server_output, true);
    //     print_array($plain_data);
    // }
    public function get_h5p_byh5pcontext($_courseid, $coursemodule, $id)
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

        $filtered_data = [];

        foreach ($plain_data['h5pactivities'] as $activity) {
            if ($activity['coursemodule'] == $coursemodule && $activity['id'] == $id && isset($activity['deployedfile'])) {
                $filtered_data[] = $activity['deployedfile'];
            }
        }
        if (!empty($filtered_data)) {
            $filtered_item = array_shift($filtered_data);
            unset($filtered_item['mimetype'], $filtered_item['timemodified'], $filtered_item['filepath'], $filtered_item['filesize']);
            // print_array($filtered_item);
            return $filtered_item;
        } else {
            return array();
        }
    }



    public function mod_book_view_book($bookid, $chapterid)
    {
        //id is 310, but we use instance i.e 187, chapter id: 627
        $functionname = 'mod_book_view_book';
        $data = array(
            'wstoken' => get_admin_token()['token'],
            'wsfunction' => $functionname,
            'bookid' => $bookid,
            'chapterid' => $chapterid,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $plain_data = json_decode($server_output, true);
        print_array($plain_data);
    }
    public function getbooksin_course($course_id)
    {
        $functionname = 'mod_book_get_books_by_courses';
        $data = array(
            'wstoken' => get_admin_token()['token'],
            'wsfunction' => $functionname,
            'courseids[0]' => $course_id,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $plain_data = json_decode($server_output, true);
        $final_books = array();
        if (!empty($plain_data)) {
            $plain_data_1 = $plain_data['books'];
            foreach ($plain_data_1 as $key => $value) {
                $array_per_book = array('book_id' => $value['id'], 'bookname' => $value['name']);
                array_push($final_books, $array_per_book);
            }
        }
        echo json_encode($final_books);
        // cleanContent
        //    echo json_encode()
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
