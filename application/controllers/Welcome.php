<?php

use function Amp\Iterator\merge;

defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");

class Welcome extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
    }

    public function index_temp()
    {
        $data['header'] = 'parts/header';
        $data['nav'] = 'parts/nav_home';
        $data['footer'] = 'parts/footer';
        $data['title'] = ucfirst("HE Enters");
        $this->load->view('home', $data);
    }
    public function index()
    {
        // redirect();
        // echo "<h1>Api Dashboard Comming .....</h1>";
        $this->landing(0);
    }
    public function landing($id = 0)
    {
        if ($this->session->userdata('logged_in_lodda')) {
            redirect(base_url('welcome/admin'));
        } else {
            $data['header'] = 'parts/header';
            switch ($id) {
                case 0:
                    $data['content'] = 'pages/index';
                    $this->load->view('pages/homeone', $data);
                    break;
                case 1:
                    $data['content'] = 'pages/register';
                    $this->load->view('pages/homeone', $data);
                    break;
                case 2:
                    $data['content'] = 'pages/login';
                    $this->load->view('pages/homeone', $data);
                    break;
                default:
                    redirect(base_url());
                    break;
            }
        }
    }
    public function admin($var = 0, $idnn = null, $id_twonn = null)
    {
        // print_array($this->session->userdata('logged_in_lodda'));
        // njovu
        if ($this->session->userdata('logged_in_lodda')) {
            $data['header'] = 'parts/header';
            $data['sidenav'] = 'pages/admin/navadmin';
            $data['user_profile'] = array();
            $data['survey_name'] = array();
            //Check Token 
            $checkwhatihave = $this->session->userdata('logged_in_lodda');
            $token = $checkwhatihave['token'];
            $id = $checkwhatihave['id'];
            $array_n = array(
                'token' => $token,
                'user_id' => $id
            );
            //End Token
            $new_data_url_course = base_url('user/get_moodle_courses') . '/' . $token . '/' . $id;
            $server_output = curl_request($new_data_url_course, $array_n, "get", array('App-Key: 123456'));
            $courses = json_decode($server_output, true);
            if (empty($courses)) {
                $courses = array();
            }
            $data['courses'] = $courses;
            // print_array($server_output);
            switch ($var) {
                case 0:
                    $data['content_admin'] = 'pages/admin/admin_content';
                    // print_array($server_output);
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 1:
                    $data['icon_image'] = 'https://picsum.photos/200/300';
                    $data['content_admin'] = 'pages/admin/admin_quiz';
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 2:
                    // public function selectz($array_table_n, $table_n, $variable_1, $value_1)
                    $attempt_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
                    $data['content_admin'] = 'pages/admin/surveylist';
                    $data['surveydatas'] = $attempt_n_n;
                    $this->load->view('pages/hometwo', $data);
                    // $this->load->view('pages/homequiz', $data);
                    break;
                case 3:
                    $id = $this->input->get('id');
                    $attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
                    // print_array($attempt_n_n_one);
                    $data['content_admin'] = 'pages/admin/surveyinstance';
                    $data['surveydataone'] = array_shift($attempt_n_n_one);
                    $data['id'] = $id;
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 4:
                    $data['cohorts'] = $this->getme_chort_details();
                    $data['surveys'] = $this->get_surveys();
                    $data['survey_cohort'] = $this->universal_model->join_suv_cohot();
                    $data['content_admin'] = 'pages/admin/cohorts';
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 5:
                    $id = $this->input->get('id');
                    $attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
                    $data['surveydataone'] = array_shift($attempt_n_n_one);
                    $data['content_admin'] = 'pages/admin/imgsurveyinstance';
                    $data['id'] = $id;
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 7:
                    // http://localhost/heapp/welcome/admin/7/211/10?userid=2&name=Workflow:%20Family%20Planning
                    // $persial_surveynn = $this->universal_model->join_suv_reportspecifi($id, $id_two);
                    $persial_survey = $this->universal_model->join_suv_report_details($id_twonn, $idnn);
                    $final_array = $this->report_surveydetails_data($persial_survey, $idnn);
                    $final_arrayone = array_shift($final_array);
                    $data['controller'] = $this;
                    $surveyname = $this->input->get('name');
                    $user_profile = array(
                        'username' => '<h4>' . $surveyname . '</h4>',
                        'firstname' => $final_arrayone['username'],
                        'lastname' => $final_arrayone['fullname'],
                        'submitted_date' => $final_arrayone['submitted_date'],
                    );
                    unset_post($final_arrayone, 'username');
                    unset_post($final_arrayone, 'fullname');
                    unset_post($final_arrayone, 'submitted_date');
                    $data['survey_instance'] = $final_arrayone;
                    $data['user_profile'] = $user_profile;
                    $data['content_admin'] = 'pages/admin/survey_instance';
                    $surveyname = $this->input->get('name');
                    $data['surveyname'] = $surveyname;
                    // $this->load->view('pages/hometwo', $data);
                    break;
                case 8:
                    $attempt_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
                    $data['surveydatas'] = $attempt_n_n;
                    $data['content_admin'] = 'report/survey_reportindex';
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 9:
                    $attempt_d_n_n = $this->universal_model->selectall(array('username', 'id', 'firstname', 'lastname'), 'mdl_user');
                    // $data['cohorts'] = $this->getme_chort_details();
                    $data['users'] = $attempt_d_n_n;
                    $data['content_admin'] = 'pages/admin/cohortsdownload';
                    $this->load->view('pages/hometwo', $data);
                    break;
                case 10:
                    // books_reportindex.php
                    // $attempt_d_n_n = $this->universal_model->selectall(array('username', 'id', 'firstname', 'lastname'), 'mdl_user');
                    // $attempt_d_n_n = $this->universal_model->selectall(array('username', 'id', 'firstname', 'lastname'), 'mdl_user');
                    $course_content = $this->universal_model->book_select_uniqu_by(array('course_id', 'course_shortname'), array('viewtable.course_id'));
                    //These should be returned by AJAX not a hack
                    $books_content = $this->universal_model->book_select_uniqu_by(array('book_id', 'book_name'), array('viewtable.book_name'));
                    $data['all_courses'] = $this->get_all_avail_course();
                    $data['course_content'] = $course_content;
                    $data['books_content'] = $books_content;
                    $data['content_admin'] = 'report/books_reportindex';
                    $this->load->view('pages/hometwo', $data);
                    break;
                default:
                    break;
            }
        } else {
            $data['content'] = 'pages/index';
            $this->load->view('pages/homeone', $data);
        }
    }

    public function getme_chort_details()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = $token = $this->get_admin_token()['token'];
        $functionname = 'core_cohort_get_cohorts';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'cohortids' => array(),

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
        // print_array($array_of_output);
    }


    public function get_surveys()
    {
        $attempt_d_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
        $array_object = array();
        foreach ($attempt_d_n_n as $key => $value) {
            $custome_onw = array(
                'id' => $value['id'],
                'fullname' => $value['name'],
                'categoryid' => 2,
                'source' => $value['type'],
                'summary_custome' => $value['surveydesc'],
                "next_link" => base_url('survey/getnexlink/') . $value['id'],
                'image_url_small' => base_url('uploadscustome/') . $value['image'],
                'image_url' => base_url('uploadscustome/') . $value['image_url_small']
            );
            array_push($array_object, $custome_onw);
        }
        return $array_object;
    }

    #Test Get User Details
    public function get_meuserdetails($user_id)
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
            'values[0]' => $user_id

        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        // $mamama = $this->session->userdata('logged_in_lodda');
        // return $array_of_output;
        // nakafeero_teddy
        return $array_of_output;
        // print_array($array_of_output);
    }
    public function report_surveydetails_data_temp($persial_survey, $id)
    {
        print_array($persial_survey);
    }
    public function report_surveydetails_data($persial_survey, $id)
    {
        $array_object = array();
        foreach ($persial_survey as $key => $value_object) {
            $user_details_output = $this->get_meuserdetails($value_object['userid']);
            $jaja_raary = array_shift($user_details_output);
            $surveyobject = json_decode($value_object['surveyobject'], true);
            $surveyjson = json_decode($value_object['surveyjson'], true);
            $arrayn = array(
                'username' => $jaja_raary['username'],
                'fullname' => $jaja_raary['fullname'],
                'submitted_date' => $value_object['dateaddedsurvey'],
                // 'name' => $value_object['name'],
                'surveyobject' => $surveyobject,
                'surveyjson' => $surveyjson
            );
            array_push($array_object, $arrayn);
        }
        $array_of_arraymega = array();
        $int_key = 0;
        $key_then = 0;
        foreach ($array_object as $keyn => $value_n) {
            $array_of_array = array();
            $array_of_array['username'] = $value_n['username'];
            $array_of_array['fullname'] = $value_n['fullname'];
            $array_of_array['submitted_date'] = $value_n['submitted_date'];
            // $array_of_array['name'] = $value_n['name'];
            $surveyobject = $value_n['surveyobject'];
            $surveyjson = $value_n['surveyjson']['pages'];
            foreach ($surveyobject as $keya => $valuea) {
                foreach ($surveyjson as $keyb => $valueb) {
                    $elements = $valueb['elements'];
                    foreach ($elements as $keyc => $valuec) {
                        if ($valuec['type'] == "radiogroup" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            $arrayc = array(
                                'type' => $valuec['type'],
                                'title' => $valuec['title'],
                                // 'description' => $valuec['description'],
                            );
                            if (array_key_exists('description', $valuec)) {
                                $arrayc['description'] = $valuec['description'];
                            } else {
                                $arrayc['description'] = "";
                            }
                            $getvalue = recursive_array_search($valuea, $valuec['choices']);
                            if (!empty($getvalue)) {
                                $getvaluezero = $getvalue[0];
                                $value_n = $valuec['choices'][$getvaluezero];
                                $arrayc['text'] = $value_n['text'];
                                $arrayc['value'] = $value_n['value'];
                                // print_array($value_n);
                            }
                            array_push($array_of_array, $arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "radiogroup") {
                            if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                                $arrayc = array(
                                    'type' => $valuec['type'],
                                    'title' => $valuec['title'],
                                    // 'description' => $valuec['description'],
                                );
                                if (array_key_exists('description', $valuec)) {
                                    $arrayc['description'] = $valuec['description'];
                                } else {
                                    $arrayc['description'] = "";
                                }
                                $getvalue = recursive_array_search($valuea, $valuec['choices']);
                                if (!empty($getvalue)) {
                                    $getvaluezero = $getvalue[0];
                                    $value_n = $valuec['choices'][$getvaluezero];
                                    $arrayc['text'] = $value_n['text'];
                                    $arrayc['value'] = $value_n['value'];
                                    // print_array($value_n);
                                }
                                array_push($array_of_array, $arrayc);
                            }
                        }
                        if ($valuec['type'] == "checkbox" && $valuec['name'] == $keya) {
                            $string_values_mama = "";
                            foreach ($valuea as $keymama => $valuemama) {
                                $getvalue = recursive_array_search($valuemama, $valuec['choices']);
                                if (!empty($getvalue)) {
                                    $getvaluezero = $getvalue[0];
                                    $value_n = $valuec['choices'][$getvaluezero];
                                    // $arraycmama['text'] = $value_n['text'];
                                    // $arraycmama['value'] = $value_n['value'];
                                    // array_push($array_valuemama, $arraycmama);
                                    $string_values_mama .= ", " . $value_n['text'];
                                }
                            }
                            if ($string_values_mama != "") {
                                $arrayc = array(
                                    'type' => $valuec['type'],
                                    'title' => $valuec['title'],
                                    // 'description' => $valuec['description'],
                                );
                                if (array_key_exists('description', $valuec)) {
                                    $arrayc['description'] = $valuec['description'];
                                } else {
                                    $arrayc['description'] = "";
                                }
                                $arrayc['text'] = ltrim($string_values_mama, $string_values_mama[0]);
                                $arrayc['value'] = $valuea[0];
                                // print_array($string_values_mama);
                                array_push($array_of_array, $arrayc);
                            }
                        }
                        if ($valuec['type'] == "html" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            // $arrayc = array(
                            //     'type' => $valuec['type'],
                            //     'title' => "html_info",
                            // );
                            // if (array_key_exists('description', $valuec)) {
                            //     $arrayc['description'] = $valuec['description'];
                            // } else {
                            //     $arrayc['description'] = "";
                            // }
                            // $value_n = $this->cleanContent($valuec['html']);
                            // $arrayc['text'] = $$valuec['html'];
                            // $arrayc['value'] = "html_value";
                            // array_push($array_of_array, $arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "html") {
                            if (is_array($valuea)) {
                            } else {
                                // if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                                //     $arrayc = array(
                                //         'type' => $valuec['type'],
                                //         'title' => "html_info",
                                //         'description' => "",
                                //     );
                                //     $value_n = $this->cleanContent($valuec['html']);
                                //     $arrayc['text'] = $valuec['html'];
                                //     $arrayc['value'] = "html_value";
                                //     array_push($array_of_array, $arrayc);
                                // }
                            }
                        }
                        //Start Test
                        if ($valuec['type'] == "text" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            // print_array($keya);
                            $title_non_nill="";
                            if(array_key_exists('title',$valuec)){
                                $title_non_nill=$valuec['title'];
                            }
                            $arrayc = array(
                                'type' => $valuec['type'],
                                'title' => $title_non_nill,
                            );
                            if (array_key_exists('description', $valuec)) {
                                $arrayc['description'] = $valuec['description'];
                            } else {
                                $arrayc['description'] = "";
                            }
                            $arrayc['text'] = $surveyobject[$keya];
                            $arrayc['value'] = $keya;
                            array_push($array_of_array, $arrayc);
                            // print_array($arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "text") {
                            if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                                $arrayc = array(
                                    'type' => $valuec['type'],
                                    'title' => $valuec['title'],
                                );
                                if (array_key_exists('description', $valuec)) {
                                    $arrayc['description'] = $valuec['description'];
                                } else {
                                    $arrayc['description'] = "";
                                }
                                //Triky One
                                $key_value = $valuec['name'];
                                $arrayc['text'] = $surveyobject[$key_value];
                                $arrayc['value'] = $keya;
                                array_push($array_of_array, $arrayc);
                                // print_array($valuec);
                            }
                            // print_array($arrayc);
                        }
                        //End Test
                        if ($valuec['type'] == "file" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            $title_non_nill="";
                            if(array_key_exists('title',$valuec)){
                                $title_non_nill=$valuec['title'];
                            }
                            $arrayc = array(
                                'type' => $valuec['type'],
                                'title' => $title_non_nill,
                            );
                            if (array_key_exists('description', $valuec)) {
                                $arrayc['description'] = $valuec['description'];
                            } else {
                                $arrayc['description'] = "";
                            }
                            //Tricky

                            //Old Support Version 1
                            $attempt_n_n_one = $this->universal_model->selectzy('imageifany', 'survey_report', 'id', $id, 'imageifany', "none");
                            if (!empty($attempt_n_n_one) && is_array($surveyobject[$keya])) {
                                $jaja_image = array_shift($surveyobject[$keya]);
                                if (!empty($jaja_image)) {
                                    $name_final = getToken(10) . $jaja_image['name'];
                                    $one = $jaja_image['content'];
                                    $two = str_replace("data:image/jpeg;base64,", "", $one);
                                    // data:image/jpeg;base64,
                                    // $value_baby['image_base_obj'] = $two;
                                    $arrayc['text'] = $name_final;
                                    $arrayc['value'] = $keya;
                                    $path = FCPATH . "uploadsurvey/" . $name_final;
                                    $status = file_put_contents($path, base64_decode($two));
                                    if ($status) {
                                        // public function updatez($variable, $value, $table_name, $updated_values)
                                        $this->universal_model->updatez("id", $id, "survey_report", array('imageifany' => $name_final));
                                    }
                                } else {
                                    $value_baby['value_name'] = "";
                                }
                            } elseif (is_array($surveyobject[$keya])) {
                                //For more than 1 image scenerio.
                                $jaja_image = array_shift($surveyobject[$keya]);
                                if (!empty($jaja_image)) {
                                    $name_final = $jaja_image['name'];
                                    $attempt_n_n_two = $this->universal_model->selectz('id', 'survey_image', 'image_name', $name_final);
                                    if(empty($attempt_n_n_two)){
                                        $one = $jaja_image['content'];
                                        $two = str_replace("data:image/jpeg;base64,", "", $one);
                                    $arrayc['text'] = $name_final;
                                    $arrayc['value'] = $keya;
                                    $path = FCPATH . "uploadsurvey/" . $name_final;
                                    $status = file_put_contents($path, base64_decode($two));
                                    if ($status) {
                                        $array_image_survey = array(
                                            'image_name' => $name_final,
                                            'user_id' => 0,
                                            'survey_id' => $id
                                        );
                                        $this->universal_model->updateOnDuplicate('survey_image', $array_image_survey);
                                    }
                                    }else{
                                        $arrayc['text'] = $name_final;
                                        $arrayc['value'] = $keya;
                                    }
                                    
                                } else {
                                    $value_baby['value_name'] = "";
                                }
                            }
                            else {
                                // * Start New Image Versions
                                // * To Be Back
                                    $names_image=$surveyobject[$keya];
                                    $attempt_n_n_two = $this->universal_model->selectz('id', 'survey_image', 'image_name', $names_image);
                                if (!empty($attempt_n_n_two)) {
                                    $arrayc['text'] = $surveyobject[$keya];
                                    $arrayc['value'] = $keya;
                                }
                                // * End  New Image Versions
                                else {
                                    $attempt_n_n_one = $this->universal_model->selectz('imageifany', 'survey_report', 'id', $id);
                                    $array_one = array_shift($attempt_n_n_one);
                                    $arrayc['text'] = $array_one['imageifany'];
                                    $arrayc['value'] = $keya;
                                }
                            }
                            //End Tricky
                            // $arrayc['text'] = $jaja_image;
                            // $arrayc['value'] = $keya;
                            array_push($array_of_array, $arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "file") {
                            if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                                $arrayc = array(
                                    'type' => $valuec['type'],
                                    'title' => $valuec['title'],
                                );
                                if (array_key_exists('description', $valuec)) {
                                    $arrayc['description'] = $valuec['description'];
                                } else {
                                    $arrayc['description'] = "";
                                }
                                //Tricky
                                $attempt_n_n_one = $this->universal_model->selectzy('imageifany', 'survey_report', 'id', $id, 'imageifany', "none");
                                if (!empty($attempt_n_n_one) && is_array($surveyobject[$keya])) {
                                    print_array("Here We are pup");
                                    $jaja_image = array_shift($surveyobject[$keya]);
                                    if (!empty($jaja_image)) {
                                        $name_final = getToken(10) . $jaja_image['name'];
                                        $one = $jaja_image['content'];
                                        $two = str_replace("data:image/jpeg;base64,", "", $one);
                                        // data:image/jpeg;base64,
                                        // $value_baby['image_base_obj'] = $two;
                                        $arrayc['text'] = $name_final;
                                        $arrayc['value'] = $keya;
                                        $path = FCPATH . "uploadsurvey/" . $name_final;
                                        $status = file_put_contents($path, base64_decode($two));
                                        if ($status) {
                                            // public function updatez($variable, $value, $table_name, $updated_values)
                                            $this->universal_model->updatez("id", $id, "survey_report", array('imageifany' => $name_final));
                                        }
                                    } else {
                                        $value_baby['value_name'] = "";
                                    }
                                } 
                                elseif (is_array($surveyobject[$keya])) {
                                    print_array("Here We are");
                                    //For more than 1 image scenerio.
                                    // $jaja_image = array_shift($surveyobject[$keya]);
                                    // if (!empty($jaja_image)) {
                                    //     $name_final = $jaja_image['name'];
                                    //     $attempt_n_n_two = $this->universal_model->selectz('id', 'survey_image', 'image_name', $name_final);
                                    //     if(empty($attempt_n_n_two)){
                                    //         $one = $jaja_image['content'];
                                    //         $two = str_replace("data:image/jpeg;base64,", "", $one);
                                    //     $arrayc['text'] = $name_final;
                                    //     $arrayc['value'] = $keya;
                                    //     $path = FCPATH . "uploadsurvey/" . $name_final;
                                    //     $status = file_put_contents($path, base64_decode($two));
                                    //     if ($status) {
                                    //         $array_image_survey = array(
                                    //             'image_name' => $name_final,
                                    //             'user_id' => 0,
                                    //             'survey_id' => $id
                                    //         );
                                    //         $this->universal_model->updateOnDuplicate('survey_image', $array_image_survey);
                                    //     }
                                    //     }else{
                                    //         $arrayc['text'] = $name_final;
                                    //         $arrayc['value'] = $keya;
                                    //     }
                                        
                                    // } else {
                                    //     $value_baby['value_name'] = "";
                                    // }
                                }
                                else {
                                    //Start New Image Versions
                                    $attempt_n_n_two = $this->universal_model->selectz('id', 'survey_image', 'image_name', $surveyobject[$keya]);
                                    print_array($attempt_n_n_two);
                                    if (!empty($attempt_n_n_two)) {
                                        $arrayc['text'] = $surveyobject[$keya];
                                        $arrayc['value'] = $keya;
                                        print_array("Here We are xx");
                                    }
                                    //End  New Image Versions
                                    else {
                                        print_array($surveyobject[$keya]);
                                        $attempt_n_n_one = $this->universal_model->selectz('imageifany', 'survey_report', 'id', $id);
                                        $array_one = array_shift($attempt_n_n_one);
                                        $arrayc['text'] = $array_one['imageifany'];
                                        $arrayc['value'] = $keya;
                                        print_array("Here We are yy");
                                    }
                                }
                                //End Tricky
                                // $arrayc['text'] = $jaja_image;
                                // $arrayc['value'] = $keya;
                                array_push($array_of_array, $arrayc);
                            }
                        }
                    }
                }
            }
            $biggest = count($array_of_array);
            if ($biggest > $int_key) {
                $int_key = $biggest;
                $key_then = $keyn;
            }
            array_push($array_of_arraymega, $array_of_array);
        }
        $array_of_arraymega['key'] = $key_then;
        $array_of_arraymega['howbig'] = $int_key;
        // print_array($array_of_arraymega);
        return $array_of_arraymega;
    }
    function cleanContent($content)
    {
        $content = nl2br($content);
        $content = preg_replace('#(?:<br\s*/?>\s*?){2,}#', ' ', $content);
        return trim(strip_tags($content));
    }
    public function get_admin_token()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl/login/token.php?username=mega&password=Mega1java123!@%23&service=addusers';
        $serverurl = $domainname . '/login/token.php?';
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        //print_array($array_of_output);
        return $array_of_output;
    }
    public function get_all_avail_course()
    {
        //   core_course_get_courses
        //core_enrol_get_users_courses
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'core_course_get_courses';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $this->get_admin_token()['token'],
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $jaja = json_decode($server_output, true);
        // cleanContent
        return $jaja;
    }
}
