<?php
ini_set('max_execution_time', '3000');
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once FCPATH . 'vendor/autoload.php';

class Downloadable extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        //Downloadable Walah
    }

    public function index()
    {
        echo "<h1>Downloadable Api ..</h1>";
    }
    public function create_content()
    {
        $cohort_object = $this->input->post('cohort_object');
        $our_values = explode('@', $cohort_object);
        $hery = $this->universal_model->selectz('*', 'user', 'username', $our_values[1]);
        $datahery = array();
        $hasloggedin = 1;
        if (empty($hery)) {
            $datahery['username'] = $our_values[1];
            $datahery['password'] = '123456';
        } else {
            $varamaga = array_shift($hery);
            $datahery['username'] = $varamaga['username'];
            $datahery['password'] = $varamaga['password'];
            $hasloggedin = 2;
            # code...
        }
        $domainname = base_url();
        $serverurl = $domainname . '/moodle/login';
        $server_output = curl_request($serverurl, $datahery, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        if ($array_of_output['code'] == "500" && $hasloggedin == 2) {
            echo empty_response("User Does Not Exit", 200);
            return null;
        } elseif ($array_of_output['code'] == "500" && $hasloggedin == 1) {
            echo empty_response("User Shoud Login Atleast Once", 200);
            return null;
        }
        $user_id = $array_of_output['data']['id'];
        if (!empty($array_of_output) && array_value_recursive('profileimageurlsmall', $array_of_output)) {
            // mkdir($user_id.'images');
            // mkdir($user_id, 0755, true);
            $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
            $subpath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR;
            //User profile Images
            $imgone_path = $mypath . 'images';
            if (!is_dir($imgone_path)) {
                mkdir($imgone_path, 0755, true);
            }
            //Folder Images Survey
            $img_survey = $mypath . 'images' . DIRECTORY_SEPARATOR . 'survey';
            if (!is_dir($img_survey)) {
                mkdir($img_survey, 0755, true);
            }
            $imgn = $user_id . 'small_loginimage.' . 'png';
            $img_twon = $user_id . 'big_loginimage.' . 'png';
            $img = $imgone_path . '/' . $imgn;
            $img_two = $imgone_path . '/' . $img_twon;
            file_put_contents($img, file_get_contents(array_value_recursive('profileimageurlsmall', $array_of_output)));
            file_put_contents($img_two, file_get_contents(array_value_recursive('profileimageurl', $array_of_output)));
            //loginjson
            $dir = $mypath;
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $array_of_output['data']['profileimageurlsmall'] = '/images' . '/' . $imgn;
            $array_of_output['data']['profileimageurl'] = '/images' . '/' . $img_twon;
            $array_of_output['data']['password'] = $datahery['password'];
            $file = fopen($dir . '/' . "login.json", "w");
            $server_output_modif = json_encode($array_of_output);
            fwrite($file, $server_output_modif);
            fclose($file);
            //getcourse details 
            $data_course = array(
                'id' => $array_of_output['data']['id'],
            );
            $serverurl_course = $domainname . '/user/get_moodle_courses/' . $array_of_output['data']['token'] . '/' . $array_of_output['data']['id'];
            $server_output_course = curl_request($serverurl_course, $data_course, "post", array('App-Key: 123456'));
            $array_of_output_course = json_decode($server_output_course, true);
            //nextlink folder
            $dir_nextlink = $mypath . "next_link" . DIRECTORY_SEPARATOR;
            if (!is_dir($dir_nextlink)) {
                mkdir($dir_nextlink, 0755, true);
            }
            //survey folder
            $dir_survey = $dir_nextlink . "survey";
            if (!is_dir($dir_survey)) {
                mkdir($dir_survey, 0755, true);
            }
            $modifyied_courses = array();
            foreach ($array_of_output_course as $key => $value_course) {
                if ($value_course['source'] == "originalm") {
                    $server_output_survey = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
                    $file = fopen($dir_survey . '/' . $value_course['id'] . ".json", "w");
                    fwrite($file, $server_output_survey);
                    fclose($file);
                    $value_course['next_link'] = '/next_link/survey/' . $value_course['id'] . ".json";
                    #Handle Images For Survey
                    $image_path = $this->getme_images($img_survey, $user_id, $value_course);
                    // file_put_contents($img_two_n, file_get_contents($value_course['image_url']));
                    $value_course['image_url_small'] = '/images/survey/' . $image_path['image_url_small'];
                    $value_course['image_url'] = '/images/survey/' . $image_path['image_url'];
                } elseif ($value_course['source'] == "moodle") {
                    $value_course = $this->getmecoursecontent($value_course, $user_id)['course'];
                }
                array_push($modifyied_courses, $value_course);
            }
            $modifyied_courses_json = json_encode($modifyied_courses);
            $file = fopen($dir . '/' . 'get_moodle_courses' . ".json", "w");
            fwrite($file, $modifyied_courses_json);
            fclose($file);
            //Start Ziping
            //Zip and Download
            $tmp_file =  $subpath . $user_id . 'HE_Health.zip';
            // Initialize archive object

            //---- zipping logic -----
            $output = shell_exec('(cd ' . $mypath . ' && zip -r ' . $tmp_file . ' .)');
            //---- zipping logic -----
            $filename = 'HE Health.zip';
            $size = filesize($tmp_file); // The way to avoid corrupted ZIP
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Length: ' . $size);
            // Clean before! In order to avoid 500 error
            ob_end_clean();
            flush();
            readfile($tmp_file);
            //---

            //End Zipping
        } else {
            echo empty_response("Download Failed Contact Admin", 200);
        }
    }

    public function getme_images($img_survey, $user_id, $value_course, $pull = 0)
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
        if ($pull == 0) {
            $file_headers = @get_headers($image_url_smalloriginal);
            if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                file_put_contents($img_n, file_get_contents(base_url('uploadicons/60_user_profile_pic39K.png')));
            } else {
                file_put_contents($img_n, file_get_contents($image_url_smalloriginal));
            }
            $file_headers_n = @get_headers($image_url_original);
            if (!$file_headers_n || $file_headers_n[0] == 'HTTP/1.1 404 Not Found') {
                file_put_contents($img_two_n, file_get_contents(base_url('uploadicons/600_user_profile_pic39K.png')));
            } else {
                file_put_contents($img_two_n, file_get_contents($image_url_original));
            }
        }
        return array(
            'image_url_small' => $imgn_x,
            'image_url' => $img_twon_x
        );
    }
    public function downloadBook($server_output_book, $img_books, $dir_course_id, $relative_url, $token_nnn_u, $pull = 0)
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
                    if ($pull == 0) {
                        $file_headers_n = @get_headers($modicon_url);
                        if (!$file_headers_n || $file_headers_n[0] == 'HTTP/1.1 404 Not Found') {
                            // print_array("No image_url");
                            file_put_contents($img_two_n, file_get_contents(base_url('uploadicons/600_user_profile_pic39K.png')));
                        } else {
                            file_put_contents($img_two_n, file_get_contents($modicon_url));
                        }
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
                                if (!is_dir($img_course_perbook)) {
                                    mkdir($img_course_perbook, 0755, true);
                                }
                                $japa = $relative_url . '/' . $url_chapter . '/' . $file_name_chap;
                                $absolutepath_book = $img_course_perbook . '/' . $file_name_chap;
                                $japa = $relative_url . '/' . $url_chapter . '/' . $file_name_chap;
                                //Write Files 
                                if ($pull == 0) {
                                    $data = file_get_contents($value_in_con['filefullpath']);
                                    $fh = fopen($absolutepath_book, "w");
                                    fwrite($fh, $data);
                                    fclose($fh);
                                }
                                //Alternative Method
                                // file_put_contents($absolutepath_book, file_get_contents($value_n['filefullpath']));
                                //End Write Files
                                //End Full Path
                                $value_in_con['filefullpath'] = $japa;
                                $value_in_con['chapter_id'] = str_replace("/index.html", "", $value_in_con['href']);
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
                            $file_name_chap_nchange = str_replace("%28", "(", $file_name_chap);
                            $file_name_chap_nchange = str_replace("%29", ")", $file_name_chap_nchange);
                            $file_name_chap_nchange = str_replace("%20", " ", $file_name_chap_nchange);
                            $absolutepath_book = $img_course_perbook . '/' . $file_name_chap_nchange;
                            // print_array($file_name_chap_nchange);
                            if ($pull == 0) {
                                $_token_url_enco = str_replace(" ", "%20", $_token_url);
                                file_put_contents($absolutepath_book, file_get_contents($_token_url_enco));
                            }
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
    public function get_userdetails_internal($id = null)
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = $this->get_admin_token()['token'];
        $functionname = 'core_user_get_users_by_field';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'id',
            'values[0]' => $id

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function get_admin_token()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl/login/token.php?username=mega&password=Mega1java123!@%23&service=addusers';
        $serverurl = $domainname . '/login/token.php?';
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        // print_array($array_of_output);
        return $array_of_output;
    }

    public function getmecoursecontent($value_course, $user_id, $pull = 0)
    {
        $data_course = array(
            'id' => $user_id,
        );
        $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
        $dir_nextlink = $mypath . "next_link" . DIRECTORY_SEPARATOR;
        $course_nextlink = $value_course['next_link'];
        $course_nextlink_array = explode('/', $course_nextlink);
        $dir_get_details_percourse = $dir_nextlink . "get_details_percourse";
        if (!is_dir($dir_get_details_percourse)) {
            mkdir($dir_get_details_percourse, 0755, true);
        }
        $dir_course_id = $dir_get_details_percourse . DIRECTORY_SEPARATOR . $course_nextlink_array[count($course_nextlink_array) - 2];
        if (!is_dir($dir_course_id)) {
            mkdir($dir_course_id, 0755, true);
        }
        $server_output_book = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
        //End   Download Book
        //Change Nextlink Books
        $value_course['next_link'] = '/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
        $value_course['next_link'] =  '/next_link/get_details_percourse/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json";
        $img_course = $mypath . 'images' . DIRECTORY_SEPARATOR . 'course';
        if (!is_dir($img_course)) {
            mkdir($img_course, 0755, true);
        }
        $img_course_modicon = $mypath . 'images' . DIRECTORY_SEPARATOR . 'course' . DIRECTORY_SEPARATOR . 'modicon';
        if (!is_dir($img_course_modicon)) {
            mkdir($img_course_modicon, 0755, true);
        }
        //Post Book Phase 1
        $token_get_me = $course_nextlink_array[count($course_nextlink_array) - 1];
        $relative_url = '/next_link/get_details_percourse/' . $course_nextlink_array[count($course_nextlink_array) - 2];
        $server_opt_books_n = $this->downloadBook($server_output_book, $img_course_modicon, $dir_course_id, $relative_url, $token_get_me, $pull);
        //Start Download Book
        // modicon
        // $server_opt_books_n = json_encode($server_opt_books_n, JSON_HEX_QUOT | JSON_HEX_APOS);
        $server_opt_books_n = json_encode($server_opt_books_n);
        if ($pull == 0) {
            $file_n = fopen($dir_get_details_percourse . '/' . $course_nextlink_array[count($course_nextlink_array) - 2] . ".json", "w");
            fwrite($file_n, $server_opt_books_n);
            fclose($file_n);
        }
        $image_pathn = $this->getme_images($img_course, $user_id, $value_course, $pull);
        $value_course['image_url_small'] = '/images/course/' . $image_pathn['image_url_small'];
        $value_course['image_url'] = '/images/course/' . $image_pathn['image_url'];
        return array('course' => $value_course, 'details_course' => $server_opt_books_n);
    }





    // public function videopic()
    // {
    //     // https://github.com/PHP-FFMpeg/PHP-FFMpeg#extracting-image
    //     //https://gist.github.com/jsturgis/3b19447b304616f18657
    //     $ffmpeg = FFMpeg\FFMpeg::create();
    //     $video = $ffmpeg->open('http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4');
    //     $video->filters()->resize(new FFMpeg\Coordinate\Dimension(320, 240))->synchronize();
    //     $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))->save(FCPATH . 'excelfiles/'.'frame.jpg');
    //     // http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4
    // }
    public function book_course($user_id, $token, $id_course)
    {
        header('Content-Type: application/json');
        $array_of_output_course = $this->getme_books($token, $user_id);
        $key_course = array_search($id_course, array_column($array_of_output_course, 'id'));
        $value_course = $array_of_output_course[$key_course];
        $sample_data = $this->getmecoursecontent($value_course, $user_id, 1);
        // print_array($sample_data['details_course']);
        echo $sample_data['details_course'];
    }

    public function getme_books($token, $user_id)
    {
        $domainname = base_url('user/get_moodle_course_inter/') . $token . '/' . $user_id . '/1';
        // echo $domainname;
        $server_output = curl_request($domainname, array(), "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function book_download()
    {
        $data_copy = array(
            'user_id' => 101,
            'update_id' => 101,
            'update_type' => 'chag',
            'dateaction' => '2021-05-17 17:03:51'
        );
        $this->universal_model->updateOnDuplicate('updatetract', $data_copy);
        echo json_encode($data_copy);
    }
    public function course_download()
    {
        $data_copy = array(
            'user_id' => 100,
            'update_id' => 100,
            'update_type' => 'cheng',
            'dateaction' => '2021-05-17 17:03:51'
        );
        $this->universal_model->updateOnDuplicate('updatetract', $data_copy);
        echo json_encode($data_copy);
    }
}
