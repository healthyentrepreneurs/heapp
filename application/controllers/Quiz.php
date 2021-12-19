<?php
defined('BASEPATH') or exit('No direct script access allowed');
require "vendor/autoload.php";

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
    public function quiz_get_quiz_required_qtypes()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = '01bd8b1e707671384445694d743f6ba8';
        $functionname = 'mod_quiz_get_quiz_required_qtypes';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 7,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }

    #The Real Begining Of Quiz
    public function quiz_get_quizzes_by_courses()
    {
        # mod_quiz_get_quizzes_by_courses
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = '01bd8b1e707671384445694d743f6ba8';
        $functionname = 'mod_quiz_get_quizzes_by_courses';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'courseids[0]' => 2,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    public function quiz_view_quiz()
    {
        # mod_quiz_view_quiz
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = '01bd8b1e707671384445694d743f6ba8';
        $functionname = 'mod_quiz_view_quiz';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 7,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    public function quiz_start_attempt($quizid = null, $token)
    {
        # mod_quiz_start_attempt
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'mod_quiz_start_attempt';
        // preflightdata
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'forcenew' => 1,
            'preflightdata[0][name]' => 'quizpassword',
            'preflightdata[0][value]' => '123!@#',
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        // print_array($array_of_courses);
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
        $attempt_data_now['questions']=$formatter_clean;
        echo empty_response("Quiz Loaded .. ", 200, $attempt_data_now);
        // print_array($questions_n1);
        // echo json_encode($attempt_data_now);
    }
    //Helper Function
    public function quiz_get_attempt_data($attemptid = null, $page = 0, $token)
    {
        // https://app.healthyentrepreneurs.nl/webservice/rest/server.php?moodlewsrestformat=json&quizid=3&wsfunction=mod_quiz_get_attempt_access_information&wstoken=f84bf33b56e86a4664284d8a3dfb5280
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'mod_quiz_get_attempt_data';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'attemptid' => $attemptid,
            'page' => $page,
            'preflightdata[0][name]' => 'quizpassword',
            'preflightdata[0][value]' => '123!@#',
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        // echo empty_response("Quiz Loaded .. ", 200, $array_of_courses);
        return $array_of_courses;
    }
    public function get_user_attempts()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = '01bd8b1e707671384445694d743f6ba8';
        $functionname = 'mod_quiz_get_user_attempts';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 7,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
//Helper Function
    public function quiz_get_quiz_access_information()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = '01bd8b1e707671384445694d743f6ba8';
        $functionname = 'mod_quiz_get_quiz_access_information';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 7,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
    //This is it
    public function get_quiz_em_format($quizid, $page = 0, $token)
    {
        $check_start_quiz = $this->quiz_start_attempt($quizid, $token);
        if (array_key_exists('exception', $check_start_quiz)) {
            $attempdata = 1;
        } else {
            $check_start_quiz['attempt']['token'] = $token;
            $attempdata = $check_start_quiz['attempt']['id'];
        }
        $attempt_data_now = $this->quiz_get_attempt_data($attempdata, $page, $token);
        // print_array($attempt_data_now);
        $questions_n1 = $attempt_data_now['questions'];
        $array_questions = array();
        $html_string="";
        foreach ($questions_n1 as $key => $value) {
            // $questions_n1[$key]['html']=base64_encode($value['html']);
            $htmlscriptarray=explode("<script",$value['html']);
            $html_string=$htmlscriptarray[0]."".$html_string;
        }
        $array_questions['html']=base64_encode($html_string);
        $array_questions['layout']=count(explode($attempt_data_now['attempt']['layout']))-1;
        $array_questions['currentpage']=$attempt_data_now['attempt']['currentpage'];
        $array_questions['macrostate']=$attempt_data_now['attempt']['inprogress'];
        // $questions_n1['layout']=$attempt_data_now['attempt']['layout'];
        // [state] => inprogress
        $array_questions['nextpage']=$attempt_data_now['nextpage'];
        echo json_encode($array_questions);
    }
}
