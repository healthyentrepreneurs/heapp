<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
// https://github.com/paquettg/php-html-parser
// https://stackoverflow.com/questions/8499633/how-to-display-base64-images-in-html
// https://stackoverflow.com/questions/7214702/convert-image-to-base64-while-fetching-them-from-other-urls/7215585
use PHPHtmlParser\Dom;

header('Access-Control-Allow-Origin: *');
class Quiz extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // $this->load->library('curl');
        // $this->load->model('moodle_model', '', TRUE);
    }
    public function index()
    {
        echo "<h1>Quiz Api ...</h1>";
    }
    public function quiz_get_quiz_required_qtypes($quizid, $token)
    {
        $functionname = 'mod_quiz_get_quiz_required_qtypes';
        $data = array(
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }

    #The Real Begining Of Quiz
    public function quiz_get_quizzes_by_courses($courseid, $token)
    {
        # mod_quiz_get_quizzes_by_courses
        $functionname = 'mod_quiz_get_quizzes_by_courses';
        $data = array(
            'courseids[0]' => $courseid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    public  function get_courseresources($courseid, $token)
    {
        $functionname = 'mod_resource_get_resources_by_courses';
        $data = array(
            'courseids[0]' => $courseid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    public function quiz_view_quiz($quizid, $token)
    {
        # mod_quiz_view_quiz
        $functionname = 'mod_quiz_view_quiz';
        $data = array(
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    public function quiz_start_attempt($quizid = null, $token)
    {
        # mod_quiz_start_attempt
        $functionname = 'mod_quiz_start_attempt';
        // preflightdata
        $data = array(
            'forcenew' => 1,
            // 'preflightdata[0][name]' => 'quizpassword',
            // 'preflightdata[0][value]' => '123!@#',
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        // print_array($array_of_courses);
        if ($quizid == null) {
            return array();
        }
        if (empty($array_of_courses)) {
            return array();
            // echo empty_response("No Quiz Started .. ");
        } else {
            unset_post($array_of_courses, 'warnings');
            // print_array($array_of_courses);
            return $array_of_courses;
        }
    }
    //This is it
    public function get_quiz_em($quizid, $page = 0, $token)
    {
        $check_start_quiz = $this->quiz_start_attempt($quizid, $token);

        if (array_key_exists('exception', $check_start_quiz)) {
            $attempdata = 0;
        } else {
            $check_start_quiz['attempt']['token'] = $token;
            $attempdata = $check_start_quiz['attempt']['id'];
        }
        $attempt_data_now = $this->quiz_get_attempt_data($attempdata, $page, $token);
        $questions_n1 = $attempt_data_now['questions'];
        $formatter_clean = array();
        foreach ($questions_n1 as $key => $value) {
            $mama = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $value['html']);
            print_array($mama);
            $next_array = array(
                'html' =>  "Hey Html",
                'page' => $value['page'],
                'type' => $value['type'],
                'slot' => $value['slot'],
                'sequencecheck' => $value['sequencecheck'],
                'lastactiontime' => $value['lastactiontime'],
                'hasautosavedstep' => $value['hasautosavedstep'],
                'flagged' => $value['flagged'],
                'number' => $value['number'],
                'status' => $value['status'],
                'blockedbyprevious' => $value['blockedbyprevious'],
                'maxmark' => $value['maxmark'],

            );
            array_push($formatter_clean, $next_array);
        }
        unset_post($attempt_data_now, 'questions');
        $attempt_data_now['questions'] = $formatter_clean;
        echo empty_response("Quiz Loaded .. ", 200, $attempt_data_now);
        // print_array($questions_n1);
        // echo json_encode($attempt_data_now);
    }
    //Helper Function
    public function quiz_get_attempt_data($attemptid = null, $page = 0, $token)
    {
        $functionname = 'mod_quiz_get_attempt_data';
        $data = array(
            'attemptid' => $attemptid,
            'page' => $page,
            // 'preflightdata[0][name]' => 'quizpassword',
            // 'preflightdata[0][value]' => '123!@#',
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        // echo empty_response("Quiz Loaded .. ", 200, $array_of_courses);
        return $array_of_courses;
    }
    public function get_user_attempts($token, $quizid)
    {
        // $token = 'f9b2e4982182be83dbb7deae187a30c2';
        $functionname = 'mod_quiz_get_user_attempts';
        $data = array(
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    //Helper Function
    public function quiz_get_quiz_access_information($token, $quizid)
    {
        // $token = 'f9b2e4982182be83dbb7deae187a30c2';
        $functionname = 'mod_quiz_get_quiz_access_information';
        $data = array(
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }

    //This is it
    public function get_quiz_em_test($quizid, $page = 0, $token)
    {
        $check_start_quiz = $this->quiz_start_attempt($quizid, $token);
        if (array_key_exists('exception', $check_start_quiz)) {
            $array_arror = array(
                'code' => "400",
                'message' => limit_words($check_start_quiz['message'], 20),
                'data' => null
            );
            echo json_encode($array_arror);
            return;
        }
        $check_start_quiz['attempt']['token'] = $token;
        $attempdata = $check_start_quiz['attempt']['id'];
        $attempt_data_now = $this->quiz_get_attempt_data($attempdata, $page, $token);
        if (array_key_exists('exception', $attempt_data_now)) {
            $array_arror = array(
                'code' => "400",
                'message' => $attempt_data_now['message'],
                'data' => null
            );
            echo json_encode($array_arror);
            return;
        }
        print_array($attempt_data_now);
    }
    public function get_quiz_all_array($quizid, $page = 0, $token)
    {
        $check_start_quiz = $this->quiz_start_attempt($quizid, $token);
        if (array_key_exists('exception', $check_start_quiz)) {
            $array_arror = array(
                'code' => "400",
                'message' => limit_words($check_start_quiz['message'], 25),
                'data' => null
            );
            echo json_encode($array_arror);
            return;
        }
        $check_start_quiz['attempt']['token'] = $token;
        $attempdata = $check_start_quiz['attempt']['id'];
        $attempt_data_now = $this->quiz_get_attempt_data($attempdata, $page, $token);
        // print_array($attempt_data_now);
        if (array_key_exists('exception', $attempt_data_now)) {
            $array_arror = array(
                'code' => "400",
                'message' => $attempt_data_now['message'],
                'data' => null
            );
            echo json_encode($array_arror);
            return;
        }
        $questions_n1 = $attempt_data_now['questions'];
        $array_questions = array();
        $html_string = "";
        foreach ($questions_n1 as $key => $value) {
            // $questions_n1[$key]['html']=base64_encode($value['html']);
            $htmlscriptarray = explode("<script", $value['html']);
            $html_string = $htmlscriptarray[0] . "" . $html_string;
        }
        $image_handler = $this->checkdom($html_string);
        $array_questions['html'] = base64_encode($image_handler);
        $array_questions['layout'] = count(explode(",0", $attempt_data_now['attempt']['layout'])) - 1;
        $array_questions['currentpage'] = $questions_n1[0]['page'];
        // $array_questions['currentpage']=$attempt_data_now['attempt']['currentpage'];
        $array_questions['state'] = $attempt_data_now['attempt']['state'];
        // $questions_n1['layout']=$attempt_data_now['attempt']['layout'];
        // [state] => inprogress
        $array_questions['nextpage'] = $attempt_data_now['nextpage'];
        // return $array_questions;
        // header('Content-Type: application/json');
        // echo json_encode($array_questions);

        return $array_questions;
    }
    public function getmeallquizsection($quizid, $page = 0, $token)
    {
        $quizore = $this->get_quiz_all_array($quizid, $page, $token);
        // $array_questions = $this->get_quiz_all_array($quizid, $page, $token);
        $allsections_quiz = array();
        array_push($allsections_quiz, $quizore);
        for ($i = 0; $i < $quizore['layout']; $i++) {
            if ($i > 0) {
                $quiziz = $this->get_quiz_all_array($quizid, $i, $token);
                array_push($allsections_quiz, $quiziz);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($allsections_quiz);
    }
    //For Online Use
    public function get_quiz_em_format($quizid, $page = 0, $token)
    {
        $check_start_quiz = $this->quiz_start_attempt($quizid, $token);
        if (array_key_exists('exception', $check_start_quiz)) {
            $array_arror = array(
                'code' => "400",
                'message' => limit_words($check_start_quiz['message'], 25),
                'data' => null
            );
            echo json_encode($array_arror);
            return;
        }
        $check_start_quiz['attempt']['token'] = $token;
        $attempdata = $check_start_quiz['attempt']['id'];
        $attempt_data_now = $this->quiz_get_attempt_data($attempdata, $page, $token);
        // print_array($attempt_data_now);
        if (array_key_exists('exception', $attempt_data_now)) {
            $array_arror = array(
                'code' => "400",
                'message' => $attempt_data_now['message'],
                'data' => null
            );
            echo json_encode($array_arror);
            return;
        }
        $questions_n1 = $attempt_data_now['questions'];
        $array_questions = array();
        $html_string = "";
        foreach ($questions_n1 as $key => $value) {
            // $questions_n1[$key]['html']=base64_encode($value['html']);
            $htmlscriptarray = explode("<script", $value['html']);
            $html_string = $htmlscriptarray[0] . "" . $html_string;
        }
        $image_handler = $this->checkdom($html_string);
        $array_questions['html'] = base64_encode($image_handler);
        $array_questions['layout'] = count(explode(",0", $attempt_data_now['attempt']['layout'])) - 1;
        $array_questions['currentpage'] = $questions_n1[0]['page'];
        // $array_questions['currentpage']=$attempt_data_now['attempt']['currentpage'];
        $array_questions['state'] = $attempt_data_now['attempt']['state'];
        // $questions_n1['layout']=$attempt_data_now['attempt']['layout'];
        // [state] => inprogress
        $array_questions['nextpage'] = $attempt_data_now['nextpage'];
        header('Content-Type: application/json');
        echo json_encode($array_questions);
    }
    public function checkdom($quizhtmlimage)
    {
        $dom = new Dom;
        $dom->loadStr($quizhtmlimage);
        $images = $dom->find('img');
        foreach ($images as $link) {
            $src = $link->getAttribute('src');
            $newsrc = $this->getquizimage_moodleapi($src);
            $link->setAttribute('src', $newsrc);
        }
        return $dom;
    }

    public function getquizimage_moodleapi($url_image)
    {
        $URL_GET_IMAGES = MOODLEAPP_DOMAIN . "/moodleapi/api/get_file_url";
        $imagecountarray = explode("/", $url_image);
        $image = $imagecountarray[count($imagecountarray) - 1];
        if ($image != "unflagged") {
            $server_output = curl_request($URL_GET_IMAGES, array('file_name' => $image), "post", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            if ($array_of_output['status'] == 0) {
                return base_url('uploadicons/60_placeholdericon.jpeg');
                // return MOODLEAPP_DOMAIN+"/moodleapi/quizimages/image_error.jpeg";
            } else {
                return $array_of_output['image_url'];
            }
        } else {
            return $url_image;
        }
    }
    public function testImage()
    {
        echo base_url('uploadicons/60_placeholdericon.jpeg');
    }
}
