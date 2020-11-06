<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
        $token = 'f84bf33b56e86a4664284d8a3dfb5280';
        $functionname = 'mod_quiz_get_quiz_required_qtypes';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 3,
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
        #token for Mega is = de81bb4eb4e8303a15b00a5c61554e2a
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = 'de81bb4eb4e8303a15b00a5c61554e2a';
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
        $token = 'de81bb4eb4e8303a15b00a5c61554e2a';
        $functionname = 'mod_quiz_view_quiz';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 3,
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
        #de81bb4eb4e8303a15b00a5c61554e2a
        $domainname = 'https://app.healthyentrepreneurs.nl';
        // $token = 'de81bb4eb4e8303a15b00a5c61554e2a';
        $functionname = 'mod_quiz_start_attempt';
        // preflightdata
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            // 'forcenew' => 1,
            'preflightdata[0][name]' => 'quizpassword',
            'preflightdata[0][value]' => '123!@#',
            'quizid' => $quizid,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
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
            $attempt_d_n_n = $this->universal_model->selectzy('*', 'quiz_track', 'token', $token, 'quiz', $quizid);
            $attempt_d_n = array_shift($attempt_d_n_n);
            $attempdata = $attempt_d_n['id'];
        } else {
            $check_start_quiz['attempt']['token'] = $token;
            $this->universal_model->updateOnDuplicate('quiz_track', $check_start_quiz['attempt']);
            $attempdata = $check_start_quiz['attempt']['id'];
        }
        $attempt_data_now = $this->quiz_get_attempt_data($attempdata, $page, $token);
        $questions_n1 = $attempt_data_now['questions'];
        // $formatter_clean = array();
        // unset_post($attempt_data_now, 'questions');
        // $attempt_data_now['questions'] = $formatter_clean;
        echo empty_response("Quiz Loaded .. ", 200, $attempt_data_now);
        // print_array($questions_n1);
        // echo json_encode($attempt_data_now);
    }
    function Dom2Array($root)
    {
        $array = array();

        //list attributes
        if ($root->hasAttributes()) {
            foreach ($root->attributes as $attribute) {
                $array['_attributes'][$attribute->name] = $attribute->value;
            }
        }

        //handle classic node
        if ($root->nodeType == XML_ELEMENT_NODE) {
            $array['_type'] = $root->nodeName;
            if ($root->hasChildNodes()) {
                $children = $root->childNodes;
                for ($i = 0; $i < $children->length; $i++) {
                    $child = $this->Dom2Array($children->item($i));

                    //don't keep textnode with only spaces and newline
                    if (!empty($child)) {
                        $array['_children'][] = $child;
                    }
                }
            }

            //handle text node
        } elseif ($root->nodeType == XML_TEXT_NODE || $root->nodeType == XML_CDATA_SECTION_NODE) {
            $value = $root->nodeValue;
            if (!empty($value)) {
                $array['_type'] = '_text';
                $array['_content'] = $value;
            }
        }

        return $array;
    }
    public function quiz_get_attempt_data($attemptid = null, $page = 0, $token)
    {
        // https://app.healthyentrepreneurs.nl/webservice/rest/server.php?moodlewsrestformat=json&quizid=3&wsfunction=mod_quiz_get_attempt_access_information&wstoken=f84bf33b56e86a4664284d8a3dfb5280
        $domainname = 'https://app.healthyentrepreneurs.nl';
        // $token = 'de81bb4eb4e8303a15b00a5c61554e2a';
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
        $token = 'de81bb4eb4e8303a15b00a5c61554e2a';
        $functionname = 'mod_quiz_get_user_attempts';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 3,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }

    public function quiz_get_quiz_access_information()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = 'de81bb4eb4e8303a15b00a5c61554e2a';
        $functionname = 'mod_quiz_get_quiz_access_information';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'quizid' => 3,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        print_array($array_of_courses);
    }
}
