<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once FCPATH . 'vendor/autoload.php';

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Adapter\InMemory as InMemoryAdapter;
use Gaufrette\StreamWrapper;


class Vicking extends CI_Controller
{
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
        $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
        $subpath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR;
        $img_survey = $mypath . 'images' . DIRECTORY_SEPARATOR . 'survey';
        #END BASE PATHS
        //For Images Surveys
        $imgone_path = $mypath . 'images';
        $imgn = $user_id . 'small_loginimage.' . 'png';
        $img_twon = $user_id . 'big_loginimage.' . 'png';
        //End Image  Surveys
        $adapter = new LocalAdapter($imgone_path, true);
        $filesystem = new Filesystem($adapter);
        $FileObjectA = $filesystem->createFile($imgn);
        $FileObjectA->setContent(file_get_contents($array_of_output_data['profileimageurlsmall']));
        $FileObjectB = $filesystem->createFile($img_twon);
        $FileObjectB->setContent(file_get_contents($array_of_output_data['profileimageurl']));
        // Next 1
        $array_of_output['data']['profileimageurlsmall'] = '/images' . '/' . $imgn;
        $array_of_output['data']['profileimageurl'] = '/images' . '/' . $img_twon;
        $array_of_output['data']['password'] = $user_creds['password'];
        $adapter = new InMemoryAdapter(array('login.json' => json_encode($array_of_output)));
        $filesystem = new Filesystem($adapter);
        $map = StreamWrapper::getFilesystemMap();
        $map->set('foo', $filesystem);
        StreamWrapper::register();
        copy('gaufrette://foo/login.json', $mypath . 'login.json');
        unlink('gaufrette://foo/login.json');
        //Next 2
        //getcourse details 
        $data_course = array(
            'id' => $array_of_output['data']['id'],
        );
        $serverurl_course = $domainname . '/user/get_moodle_courses/' . $array_of_output['data']['token'] . '/' . $array_of_output['data']['id'];
        $server_output_course = curl_request($serverurl_course, $data_course, "post", array('App-Key: 123456'));
        $array_of_output_course = json_decode($server_output_course, true);
        //Next Gen Links 1
        $dir_nextlink = $mypath . "next_link" . DIRECTORY_SEPARATOR;
        $dir_survey = $dir_nextlink . "survey";
        $adapter_survey = new LocalAdapter($dir_survey, true);
        $filesystem_survey = new Filesystem($adapter_survey);
        $modifyied_courses = array();
        foreach ($array_of_output_course as $key => $value_course) {
            if ($value_course['source'] == "originalm") {
                $server_output_survey = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
                $name_en = $value_course['id'] . ".json";
                $FileObjectS = $filesystem_survey->createFile($name_en);
                $FileObjectS->setContent($server_output_survey);
                $value_course['next_link'] = '/next_link/survey/' . $name_en;
                $image_path = $this->getme_images($img_survey, $user_id, $value_course);
                $value_course['image_url_small'] = '/images/survey/' . $image_path['image_url_small'];
                $value_course['image_url'] = '/images/survey/' . $image_path['image_url'];
                // print_array($server_output_survey);
            } elseif ($value_course['source'] == "moodle") {
                $course_nextlink = $value_course['next_link'];
                $course_nextlink_array = explode('/', $course_nextlink);
                $dir_get_details_percourse = $dir_nextlink . "get_details_percourse";
                $dir_course_id = $dir_get_details_percourse . DIRECTORY_SEPARATOR . $course_nextlink_array[count($course_nextlink_array) - 2];
                $server_output_book = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
                $value_course['next_link'] = '/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
                $value_course['next_link'] =  '/next_link/get_details_percourse/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
                $img_course = $mypath . 'images' . DIRECTORY_SEPARATOR . 'course';
                $img_course_modicon = $mypath . 'images' . DIRECTORY_SEPARATOR . 'course' . DIRECTORY_SEPARATOR . 'modicon';
                $token_get_me = $course_nextlink_array[count($course_nextlink_array) - 1];
                $relative_url = '/next_link/get_details_percourse/' . $course_nextlink_array[count($course_nextlink_array) - 2];
                //Roll The Book
                #Space Start
                // $tetst_content = json_decode($server_output_book, true);
                // $jaja_nana = $tetst_content['data'];
                // print_array(count($jaja_nana));
                #Space End
                $server_opt_books_n = $this->downloadBook($server_output_book, $img_course_modicon, $dir_course_id, $relative_url, $token_get_me);
                $json_server_opt_books_n=json_encode($server_opt_books_n);
                // print_array($server_opt_books_n);
                $adapter_final = new LocalAdapter($dir_get_details_percourse, true);
                $filesystem_final = new Filesystem($adapter_final);
                $name_final = $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
                $FileObject_final = $filesystem_final->createFile($name_final);
                $FileObject_final->setContent($json_server_opt_books_n);
                $image_pathn = $this->getme_images($img_course, $user_id, $value_course);
                $value_course['image_url_small'] = '/images/course/' . $image_pathn['image_url_small'];
                $value_course['image_url'] = '/images/course/' . $image_pathn['image_url'];
            }
            array_push($modifyied_courses, $value_course);
        }
        //End Next Gen Links 2
        // $adapter = new InMemoryAdapter(array($imgn =>file_get_contents($array_of_output_data['profileimageurlsmall'])));
        // $filesystem = new Filesystem($adapter);
        // $map = StreamWrapper::getFilesystemMap();
        // $map->set('foo', $filesystem);
        // StreamWrapper::register();
        // copy('gaufrette://foo/' . $imgn, $mypath . $imgn);
        // unlink('gaufrette://foo/' . $imgn);
    }

    public function downloadBook($server_output_book, $img_books, $dir_course_id, $relative_url, $token_nnn_u, $lang = "LU")
    {
        $server_output = json_decode($server_output_book, true);
        if (empty($server_output)) {
            return array();
        } else {
            $array_data = $server_output['data'];
            $array_merger = array();
            $array_merger['code'] = $server_output['code'];
            $array_merger['msg'] = $server_output['msg'];
            $array_data_fuck = array();
            foreach ($array_data as $key => $value_from_data) {
                // print_array($value_from_data);
                $modules_section = $value_from_data['modules'];
                unset_post($value_from_data, 'modules');
                $array_n_n = array();
                foreach ($modules_section as $key => $modules_values) {
                    //Start Mid Icon
                    $modicon_url = $modules_values['modicon'];
                    $modicon_url_arr = explode('/', $modicon_url);
                    $imgn_icon = $modicon_url_arr[count($modicon_url_arr) - 1];
                    $img_two_n = $img_books . '/' . $imgn_icon;
                    $file_headers_n = @get_headers($modicon_url);
                    $adapter_icon_image = new LocalAdapter($img_books, true);
                    $filesyste_iconimage = new Filesystem($adapter_icon_image);
                    if (!$file_headers_n || $file_headers_n[0] == 'HTTP/1.1 404 Not Found') {
                        $FileObject_n = $filesyste_iconimage->createFile($imgn_icon);
                        $FileObject_n->setContent(file_get_contents(base_url('uploadicons/60_user_profile_pic39K.png')));
                    } else {
                        $FileObject_n = $filesyste_iconimage->createFile($imgn_icon);
                        $FileObject_n->setContent(file_get_contents($modicon_url));
                    }
                    $modules_values['modicon'] = '/images' . DIRECTORY_SEPARATOR . 'course' . DIRECTORY_SEPARATOR . 'modicon' . '/' . $imgn_icon;
                    //End   Mid Icon
                    // contents
                    $a_two = $modules_values['contents'];
                    unset_post($modules_values, 'contents');
                    $contents_array = array();
                    foreach ($a_two as $key => $value) {
                        if ($value['type'] == "content") {
                            $value_content = $value['content'];
                            unset_post($value, 'content');
                            $_to_value = json_decode($value_content, true);
                            $value_content_array = array();
                            foreach ($_to_value as $key => $value_in_con) {
                                //For Full Path In Content
                                $filefullpath = $value_in_con['filefullpath'];
                                $filefullpatharray = explode('?', $filefullpath);
                                $filefull_url = $filefullpatharray[0];
                                $token_get = $filefullpatharray[1];
                                $filefullarray = explode('/', $filefull_url);
                                unset_post($filefullarray, 0);
                                unset_post($filefullarray, 1);
                                unset_post($filefullarray, 2);
                                unset_post($filefullarray, 3);
                                unset_post($filefullarray, 4);
                                $key_last_chap = @end(array_keys($filefullarray));
                                $file_name_chap = $filefullarray[$key_last_chap];
                                unset_post($filefullarray, $key_last_chap);
                                $url_chapter = implode("/", $filefullarray);
                                $img_course_perbook = $dir_course_id . '/' . $url_chapter;
                                // if (!is_dir($img_course_perbook)) {
                                //     mkdir($img_course_perbook, 0755, true);
                                // }
                                $japa = $relative_url . '/' . $url_chapter . '/' . $file_name_chap;
                                $absolutepath_book = $img_course_perbook . '/' . $file_name_chap;
                                $japa = $relative_url . '/' . $url_chapter . '/' . $file_name_chap;
                                //Write Files 
                                // $data = file_get_contents($value_in_con['filefullpath']);
                                // $fh = fopen($absolutepath_book, "w");
                                // fwrite($fh, $data);
                                // fclose($fh);
                                // $adapter = new LocalAdapter($img_course_perbook, true);
                                // $filesystem = new Filesystem($adapter);
                                // $FileObjectA = $filesystem->createFile($file_name_chap);
                                // $FileObjectA->setContent(file_get_contents($value_in_con['filefullpath']));
                                //Alternative Method
                                // file_put_contents($absolutepath_book, file_get_contents($value_n['filefullpath']));
                                //End Write Files
                                //End Full Path
                                $value_in_con['filefullpath'] = $japa;
                                array_push($value_content_array, $value_in_con);
                            }
                            $jajama = json_encode($value_content_array);
                            $value['content'] = $jajama;
                        }
                        if ($value['type'] == "file") {
                            $filearray = explode('/', $value['fileurl']);
                            unset_post($filearray, 0);
                            unset_post($filearray, 1);
                            unset_post($filearray, 2);
                            unset_post($filearray, 3);
                            unset_post($filearray, 4);
                            $key_last_chap = @end(array_keys($filearray));
                            $file_name_chap = $filearray[$key_last_chap];
                            unset_post($filearray, $key_last_chap);
                            $url_chapter = implode("/", $filearray);
                            $img_course_perbook = $dir_course_id . '/' . $url_chapter;
                            $japa = $relative_url . '/' . $url_chapter . '/' . $file_name_chap;
                            $absolutepath_book = $img_course_perbook . '/' . $file_name_chap;
                            $_token_url = $value['fileurl'] . '?token=' . $token_nnn_u;
                            //Start Writing
                            if ($value['filename'] == "index.html") {
                                // $adapter = new LocalAdapter($img_course_perbook, true);
                                // $filesystem = new Filesystem($adapter);
                                // $FileObjectA = $filesystem->createFile($file_name_chap);
                                // $FileObjectA->setContent(file_get_contents($_token_url));
                            }
                            // file_put_contents($absolutepath_book, file_get_contents($_token_url));
                            //Stop Writing
                            $value['fileurl'] = $japa;
                        }
                        array_push($contents_array, $value);
                    }
                    $modules_values['contents'] = $contents_array;
                    array_push($array_n_n, $modules_values);
                }
                $value_from_data['modules'] = $array_n_n;
                array_push($array_data_fuck, $value_from_data);
            }
            $array_merger['data'] = $array_data_fuck;
            return $array_merger;
        }
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
        //Create Put Content
        $adapter_surveyimage = new LocalAdapter($img_survey, true);
        $filesyste_surveyimage = new Filesystem($adapter_surveyimage);
        // $FileObjectA = $filesystem->createFile($imgn_x);
        // $FileObjectA->setContent(file_get_contents($array_of_output_data['profileimageurlsmall']));
        //End Create Content 
        $file_headers = @get_headers($image_url_smalloriginal);
        if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
            $FileObject_n = $filesyste_surveyimage->createFile($imgn_x);
            $FileObject_n->setContent(file_get_contents(base_url('uploadicons/60_user_profile_pic39K.png')));
            // file_put_contents($img_n, file_get_contents(base_url('uploadicons/60_user_profile_pic39K.png')));
        } else {
            $FileObject_n = $filesyste_surveyimage->createFile($imgn_x);
            $FileObject_n->setContent(file_get_contents($image_url_smalloriginal));
        }
        $file_headers_n = @get_headers($image_url_original);
        if (!$file_headers_n || $file_headers_n[0] == 'HTTP/1.1 404 Not Found') {
            $FileObject_n = $filesyste_surveyimage->createFile($img_twon_x);
            $FileObject_n->setContent(file_get_contents(base_url('uploadicons/600_user_profile_pic39K.png')));
        } else {
            $FileObject_n = $filesyste_surveyimage->createFile($img_twon_x);
            $FileObject_n->setContent(file_get_contents($image_url_original));
        }
        return array(
            'image_url_small' => $imgn_x,
            'image_url' => $img_twon_x
        );
    }
}
