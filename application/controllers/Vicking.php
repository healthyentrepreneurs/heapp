<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once FCPATH . 'vendor/autoload.php';

use Gaufrette\Adapter\InMemory as InMemoryAdapter;
use Gaufrette\StreamWrapper;

//Try Files Manager
use ElementaryFramework\FireFS\FireFS;
//Amphp
use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\HttpException;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use Amp\Loop;

class Vicking extends CI_Controller
{
    // https://github.com/amphp/http-client/tree/master/examples/streaming
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
    }

    public function index()
    {
        echo "<h1>Download Api Newer Version  ..</h1>";
    }
    public function create_content($user_id)
    {
        $vara = $this->universal_model->selectz('*', 'user', 'id_id', $user_id);
        if (empty($vara)) {
            echo empty_response("This User Does Not Exit/Shoud Login Once", 200);
            return null;
        }
        $user_creds = array_shift($vara);
        $domainname = base_url();
        $serverurl = $domainname . '/moodle/login';
        $data = array(
            'username' => $user_creds['username'],
            'password' => $user_creds['password'],

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        $array_of_output_data = $array_of_output['data'];
        #BASE PATHS
        $subpath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR;
        $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
        $img_survey = $mypath . 'images' . DIRECTORY_SEPARATOR . 'survey';
        #END BASE PATHS
        //For Images Surveys
        $imgone_path = $mypath . 'images';
        $imgn = $user_id . 'small_loginimage.' . 'png';
        $img_twon = $user_id . 'big_loginimage.' . 'png';
        $img = $imgone_path . '/' . $imgn;
        $img_two = $imgone_path . '/' . $img_twon;
        $_array = array(
            $img,
            $img_two
        );
        //End Image  Surveys
        $fs = new FireFS($subpath);
        $this->dircrap($fs, $user_id);
        $this->dircrap($fs, $imgone_path);
        $this->dircrap($fs, $img_survey);
        $urls = [$array_of_output_data['profileimageurlsmall'], $array_of_output_data['profileimageurl']];
        $responsesApi = $this->getmeApione($urls);
        $_i = 0;
        foreach ($responsesApi as $key => $value) {
            // $fs->mkfile("./" . $_array[$_i]);
            // $fs->write("./" . $_array[$_i], $value);
            file_put_contents($_array[$_i], $value);
            $_i++;
        }
        $array_of_output['data']['profileimageurlsmall'] = '/images' . '/' . $imgn;
        $array_of_output['data']['profileimageurl'] = '/images' . '/' . $img_twon;
        $array_of_output['data']['password'] = $user_creds['password'];
        $serverurl_course = $domainname . '/user/get_moodle_courses/' . $array_of_output['data']['token'] . '/' . $array_of_output['data']['id'];
        $data_course = array(
            'id' => $array_of_output['data']['id'],
        );
        $server_output_course = curl_request($serverurl_course, $data_course, "post", array('App-Key: 123456'));
        $array_of_output_course = json_decode($server_output_course, true);
        $dir_nextlink = $mypath . "next_link" . DIRECTORY_SEPARATOR;
        $dir_survey = $dir_nextlink . "survey";
        $this->dircrap($fs, $dir_nextlink);
        $this->dircrap($fs, $dir_survey);
        $modifyied_courses = array();
        foreach ($array_of_output_course as $key => $value_course) {
            if ($value_course['source'] == "originalm") {
                $server_output_survey = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
                $name_en = $value_course['id'] . ".json";
                $fs->setWorkingDir($dir_survey);
                $fs->mkfile("./" . $name_en);
                $fs->write("./" . $name_en, $server_output_survey);
                $value_course['next_link'] = '/next_link/survey/' . $name_en;
                $image_path = $this->getme_images($img_survey, $user_id, $value_course);
                $value_course['image_url_small'] = '/images/survey/' . $image_path['image_url_small'];
                $value_course['image_url'] = '/images/survey/' . $image_path['image_url'];
                // print_array($server_output_survey);
            } elseif ($value_course['source'] == "moodle") {
                // $course_nextlink = $value_course['next_link'];
                // $course_nextlink_array = explode('/', $course_nextlink);
                // $dir_get_details_percourse = $dir_nextlink . "get_details_percourse";
                // $server_output_book = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
                // $value_course['next_link'] = '/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
                // $value_course['next_link'] =  '/next_link/get_details_percourse/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
                // $token_get_me = $course_nextlink_array[count($course_nextlink_array) - 1];
                // $relative_url = '/next_link/get_details_percourse/' . $course_nextlink_array[count($course_nextlink_array) - 2];
                // $server_opt_books_n = $this->downloadBook($server_output_book, $img_course_modicon, $dir_course_id, $relative_url, $token_get_me);
                // $img_course = $mypath . 'images' . DIRECTORY_SEPARATOR . 'course';
                // $img_course_modicon = $mypath . 'images' . DIRECTORY_SEPARATOR . 'course' . DIRECTORY_SEPARATOR . 'modicon';
                // $this->dircrap($fs, $img_course);
                // $this->dircrap($fs, $img_course_modicon);
                // $this->dircrap($fs, $dir_get_details_percourse);
                // $image_pathn = $this->getme_images($img_course, $user_id, $value_course);
                // $value_course['image_url_small'] = '/images/course/' . $image_pathn['image_url_small'];
                // $value_course['image_url'] = '/images/course/' . $image_pathn['image_url'];
                
            }
            array_push($modifyied_courses, $value_course);
        }
        $modifyied_courses_json = json_encode($modifyied_courses);
        $fs->setWorkingDir($mypath);
        $fs->mkfile("./get_moodle_courses.json");
        $fs->write("./get_moodle_courses.json", $modifyied_courses_json);
        $fs->mkfile("./login.json");
        $fs->write("./login.json", json_encode($array_of_output));
    }
    public function dircrap($fs, $dir)
    {
        if (!$fs->exists($dir)) {
            $fs->mkdir($dir);
        }
        // return $fs;
    }
    public function getme_images($img_survey, $user_id, $value_course)
    {
        //Duplicate Images for download
        $image_url_smalloriginal = $value_course['image_url_small'];
        $image_url_original = $value_course['image_url'];
        //End
        if (strpos($value_course['image_url_small'], '?')) {
            $image_url_small_array = explode('?', $value_course['image_url_small']);
            $value_course['image_url_small'] = $image_url_small_array[0];
        }
        if (strpos($value_course['image_url'], '?')) {
            $image_url_array = explode('?', $value_course['image_url']);
            $value_course['image_url'] = $image_url_array[0];
        }
        $_image_small_arr = explode('/', $value_course['image_url_small']);
        $_image_big_arr = explode('/', $value_course['image_url']);

        $imgn_x = $user_id .  $_image_small_arr[count($_image_small_arr) - 1];
        $img_twon_x = $user_id . $_image_big_arr[count($_image_big_arr) - 1];
        $img_n = $img_survey . '/' . $imgn_x;
        $img_two_n = $img_survey . '/' . $img_twon_x;
        //Create Put Content
        // $fs->mkfile("./" . $imgn_x);
        //End Create Content 
        $_arrayn = array(
            $img_n,
            $img_two_n
        );
        $uris_def = array();
        $file_headers = @get_headers($image_url_smalloriginal);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            // print_array("No image_url_small");
            array_push($uris_def, base_url('uploadicons/60_user_profile_pic39K.png'));
        } else {
            array_push($uris_def, $image_url_smalloriginal);
        }
        $file_headers_n = @get_headers($image_url_original);
        if (!$file_headers_n || $file_headers_n[0] == 'HTTP/1.1 404 Not Found') {
            // print_array("No image_url");
            array_push($uris_def, base_url('uploadicons/600_user_profile_pic39K.png'));
        } else {
            array_push($uris_def, $image_url_original);
        }
        $responsesApin = $this->getmeApione($uris_def);
        $_i = 0;
        foreach ($responsesApin as $key => $value_t) {
            file_put_contents($_arrayn[$_i], $value_t);
            $_i++;
        }
        return array(
            'image_url_small' => $imgn_x,
            'image_url' => $img_twon_x
        );
    }
    public function getmeApione($urls, $urldefaut = null)
    {
        try {
            $client = Amp\Http\Client\HttpClientBuilder::buildDefault();
            $promises = [];
            // $urls = ['https://github.com/', 'https://google.com/', 'https://amphp.org/http-client'];
            $_i = 0;
            foreach ($urls as $url) {
                $urldefaut_value = $urldefaut[$_i];
                $promises[$url] = Amp\call(static function () use ($client, $url, $urldefaut_value) {
                    $request = new Request($url ?? $urldefaut_value);
                    // "yield" inside a coroutine awaits the resolution of the promise
                    // returned from Client::request(). The generator is then continued.
                    $response = yield $client->request($request);
                    // Same for the body here.
                    $body = yield $response->getBody()->buffer();
                    return $body;
                });
                $_i++;
            }
            $responses = Amp\Promise\wait(Amp\Promise\all($promises));
            return $responses;
        } catch (HttpException $error) {
            return $error;
        }
    }
}

// class async_file_get_contents extends Thread{
//     public $ret;
//     public $url;
//     public $finished;
//         public function __construct($url) {
//         $this->finished=false;
//         $this->url=$url;
//     }
//         public function run() {
//         $this->ret=file_get_contents($this->url);
//         $this->finished=true;
//     }
// }