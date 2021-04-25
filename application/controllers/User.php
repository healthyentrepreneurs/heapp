<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");
class User extends CI_Controller
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
        echo "<h1>Api Users Api .....</h1>";
    }
    public function get_moodle_courses($token = "de81bb4eb4e8303a15b00a5c61554e2a", $user_id = 3)
    {
        $_courses = $this->get_list_courses_internal($user_id);
        $_courses_n = array_value_recursive('id', $_courses);
        $_courses_n_array = $this->get_course_get_courses_by_ids($_courses_n, $token);
        $merge_sanitized_courses = array();
        // print_array($_courses_n_array);
        foreach ($_courses_n_array as $key => $courses) {
            $courses['source'] = "moodle";
            $courses['summary_custome'] = limit_words(strip_tags($courses['summary']), 120) . " .. ";
            $courses['next_link'] = base_url('user/get_details_percourse/' . $courses['id'] . '/' . $token);
            $courses_overviewfiles = $courses['overviewfiles'];
            if (empty($courses_overviewfiles)) {
                $courses['image_url_small'] = "https://picsum.photos/100/100";
                $courses['image_url'] = "https://picsum.photos/200/300";
            } else {
                $courses['image_url_small'] = array_shift($courses_overviewfiles)['fileurl'] . '?token=' . $token;
                $courses['image_url'] = $courses['image_url_small'];
            }
            $server_output = $this->get_details_percourse($courses['id'], $token, 2);
            if (!empty($server_output) && $courses['categoryid'] != 0) {
                $sanitized_courses = array_slice_keys($courses, array('id', 'categoryid', 'fullname', "summary_custome", 'source', 'next_link', 'image_url_small', 'image_url'));
                array_push($merge_sanitized_courses, $sanitized_courses);
            }
        }
        $array_object = $this->getme_cohort_get_cohort_members($user_id);
        $njovu = array_merge($merge_sanitized_courses, $array_object);
        echo json_encode($njovu);
    }

    public function get_list_courses_internal($user_id)
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'core_enrol_get_users_courses';
        $token = $this->get_admin_token()['token'];
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'userid' => $user_id,
            'moodlewsrestformat' => 'json'
        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            return array();
        } else {
            return $array_of_courses;
        }
    }
    public function get_details_percourse($_courseid, $token, $show = 1)
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token_x = $this->get_admin_token()['token'];
        $functionname = 'core_course_get_contents';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'courseid' => $_courseid
        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
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
                $array_modules = array();
                foreach ($_submodules['modules'] as $key => $_filter_modules) {
                    $value_check = $this->universal_model->selectzx('*', 'icon_table', 'original_one', $_filter_modules['modicon'], 'name', $_filter_modules['name'], 'type', $_filter_modules['modname']);
                    if (!empty($value_check)) {
                        $url_icon = array_shift($value_check)['image_big'];
                        $_filter_modules['modicon'] = base_url('uploadicons/' . $url_icon);
                        // print_array($_filter_modules);
                    }
                    // if ($_filter_modules['modname'] == "quiz") {
                    //     $_filter_modules['next_link'] = base_url('quiz/get_quiz_em/' . $_filter_modules['instance']);
                    // }
                    // if ($_filter_modules['modname'] == "book" || $_filter_modules['modname'] == "quiz") {
                    //     // $_filter_modules['modname'] == "forum" || 
                    //     array_push($array_modules, $_filter_modules);
                    // }
                    $new_content = array();
                    if ($_filter_modules['modname'] == "book") {
                        $contents = $_filter_modules['contents'];
                        //Array Search Manipulation
                        $contents_dub = $contents;
                        unset_post($contents_dub, 0);
                        //End Array Search Manipulation
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
                                            // $value_n['filefullpath'] = $value_check['fileurl'] . "?token=" . $token;
                                            array_push($cleaner_content, $value_n);
                                            // print_array($value_n);
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
                echo empty_response("course sections loaded", 200, $array_merger);
                // print_array($array_merger);
            } else {
                return $array_merger;
            }
        }
    }
    public function get_course_get_courses_by_ids($_courseid, $token)
    {
        if (is_array($_courseid)) {
            $string = implode(',', $_courseid);
            $domainname = 'https://app.healthyentrepreneurs.nl';
            // $token = 'f84bf33b56e86a4664284d8a3dfb5280';
            $functionname = 'core_course_get_courses_by_field';
            $serverurl = $domainname . '/webservice/rest/server.php';
            $data = array(
                'wstoken' => $token,
                'wsfunction' => $functionname,
                'moodlewsrestformat' => 'json',
                'field' => "ids",
                'value' =>  $string
            );
            $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
            // print_array($server_output);
            $array_of_courses = json_decode($server_output, true);
            if (array_key_exists('exception', $array_of_courses)) {
                // message
                return array();
            } else {
                return $array_of_courses['courses'];
            }
        } elseif (!is_null($_courseid)) {
            $arry_ids[] = $_courseid;
            $string = implode(',', $arry_ids);
            $domainname = 'https://app.healthyentrepreneurs.nl';
            // $token = 'f84bf33b56e86a4664284d8a3dfb5280';
            $functionname = 'core_course_get_courses_by_field';
            $serverurl = $domainname . '/webservice/rest/server.php';
            $data = array(
                'wstoken' => $token,
                'wsfunction' => $functionname,
                'moodlewsrestformat' => 'json',
                'field' => "ids",
                'value' =>  $string
            );
            $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
            // print_array($server_output);
            $array_of_courses = json_decode($server_output, true);
            if (array_key_exists('exception', $array_of_courses)) {
                // message
                return array();
            } else {
                return $array_of_courses['courses'];
            }
        } else {
            return array();
        }
    }
    public function set_newuser()
    {
        if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email'])) {
            $firstname     = $this->input->post('firstname');
            $lastname     = $this->input->post('lastname');
            $email           = $this->input->post('email');
            $city         = "Kampala";
            $country      = "UG";
            $description = "Auto generated description please edit";
            $user1 = new stdClass();
            $user1->username     = mb_strtolower($firstname) . '_' . mb_strtolower($lastname);
            $user1->password     = "Newuser123!";
            $user1->firstname     = $firstname;
            $user1->lastname     = $lastname;
            $user1->email         = $email;
            $user1->auth         = 'manual';
            // $user1->idnumber     = 'numberID';
            $user1->lang         = 'en';
            $user1->city         = $city;
            $user1->country     = $country;
            $user1->description = $description;
            $users = array($user1);
            $array = json_decode(json_encode($users), true);
            // $params = array();
            //Call
            $domainname = 'https://app.healthyentrepreneurs.nl';
            $token = $this->get_admin_token()['token'];
            $functionname = 'core_user_create_users';
            $serverurl = $domainname . '/webservice/rest/server.php';
            $data = array(
                'wstoken' => $token,
                'wsfunction' => $functionname,
                'moodlewsrestformat' => 'json',
                'users' => $array
            );
            $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            // print_array($array_of_output);
            if (array_key_exists('exception', $array_of_output)) {
                // message
                echo empty_response(strip_tags($array_of_output['message']));
            } else {
                // array_map(function ($v1, $v2) {
                //     echo $v1['id'] . " " . $v2['id'];
                // }, $array_of_output, $this->get_list_courses());
                foreach ($this->get_list_courses_internal($token) as $key => $course) {
                    foreach ($array_of_output as $key => $user) {
                        $this->enrol($user['id'], $course['id']);
                    }
                }
                echo empty_response("New User Successfully Created", 200, $array_of_output);
            }
        } else {
            echo empty_response("Credentials Are Required");
        }
    }
    function enrol($user_id, $course_id)
    {
        $role_id = 5; //assign role to be Student
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = $this->get_admin_token()['token'];
        $functionname = 'enrol_manual_enrol_users';
        $enrolment = array('roleid' => $role_id, 'userid' => $user_id, 'courseid' => $course_id);
        $enrolments = array($enrolment);
        // $params = array('enrolments' => $enrolments);
        $array = json_decode(json_encode($enrolments), true);
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'enrolments' => $array
        );
        curl_request($serverurl, $data, "post", array('App-Key: 123456'));
    }
    // core_cohort_get_cohort_members
    public function getme_cohort_get_cohort_members($id_quetion)
    {
        $value_check = $this->universal_model->join_suv_cohot();
        $array_ids_cohort = array();
        foreach ($value_check as $key => $value_ids) {
            $array_en_p = array(
                'survey_id' => $value_ids['sid'],
                'cohort_id' => $value_ids['cid'],
            );
            array_push($array_ids_cohort, $array_en_p);
        }
        $cohortids = array_value_recursive('cohort_id', $array_ids_cohort);
        // $cohortids = array('1', '2');
        if (empty($cohortids)) {
            return array();
        } elseif (is_string($cohortids)) {
            $cohortids = array($cohortids);
        }
        $cohortids = array_unique($cohortids);
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = $this->get_admin_token()['token'];
        $functionname = 'core_cohort_get_cohort_members';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'cohortids' => $cohortids,

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        $cohort_allowed_id = array();
        // print_array($array_of_output);
        foreach ($array_of_output as $key => $value_nop) {
            $key = array_search($id_quetion, $value_nop['userids']);
            // var_dump($crap);
            if ($key !== false) {
                $array_en_p = array(
                    'cohort_id' => $value_nop['cohortid']
                );
                array_push($cohort_allowed_id, $array_en_p);
            }
        }
        $array_object = array();
        foreach ($cohort_allowed_id as $key => $d_suvs) {
            $slect_cho_sur = $this->universal_model->join_suv_cohot(2, $d_suvs['cohort_id']);
            foreach ($slect_cho_sur as $key => $value) {
                $custome_onw = array(
                    'id' => $value['sid'],
                    'fullname' => $value['name'],
                    'categoryid' => 2,
                    'source' => $value['type'],
                    'summary_custome' => $value['surveydesc'],
                    "next_link" => base_url('survey/getnexlink/') . $value['sid'],
                    'image_url_small' => base_url('uploadscustome/') . $value['image'],
                    'image_url' => base_url('uploadscustome/') . $value['image_url_small']
                );
                array_push($array_object, $custome_onw);
            }
            // $value = array_shift($slect_cho_sur);

            // print_array($custome_onw);
        }
        // print_array($array_object);
        return $array_object;
    }
    #Test Get User Details
    public function viwedbook($book_id, $chapter_id, $token, $username = 0, $course_id = 0)
    {
        // http://localhost/m/stable_master/webservice/rest/server.php?moodlewsrestformat=json' --data 'bookid=1&chapterid=1&wsfunction=mod_book_view_book&wstoken=a70d553bbaf6d9b260a9e5c701b3c46e
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'mod_book_view_book';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'bookid' => $book_id,
            'chapterid' => $chapter_id

        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $date_received = $this->input->get('dateTime');
        $more_data = $this->test_extraction($course_id, $token, $book_id, $chapter_id, $date_received);
        //Add User Details 
        //  public function selectz($array_table_n, $table_n, $variable_1, $value_1)
        $user_step1 = $this->universal_model->selectz(array('firstname', 'lastname'), 'mdl_user', 'username', $username);
        $user_step2 = array_shift($user_step1);
        $user_step3 = implode(" ", $user_step2);
        #User End
        $_current_data = array('book_id' => $book_id, 'view_id' => $chapter_id, 'token' => $token, 'user_id' => $username, 'course_id' => $course_id, 'he_names' => $user_step3);
        $array_insert = array_merge($more_data, $_current_data);
        // return $server_output;
        // public function insertzwhere($table_name, $array_value)
        $array_of_output = json_decode($server_output, true);
        if (!empty($array_of_output)) {
            $this->universal_model->insertzwhere('viewtable', $array_insert);
            echo empty_response("New User Successfully Created", 200, $array_of_output);
        }
        // print_array($array_of_output);

    }
    public function testviews()
    {
        #THINGS TO LOOK AT
        //https://docs.moodle.org/dev/Talk:Web_service_API_functions
        //https://docs.moodle.org/dev/Events_API
        //https://rdrr.io/github/jchrom/moodler/f/README.md
        $mama = $this->viwedbook(104, 268, 'b536dbacaab00ab6924ddd9798a1a611');
        print_array($mama);
        // $domainname = 'https://app.healthyentrepreneurs.nl';
        // $functionname = 'mod_book_get_books_by_courses';
        // $serverurl = $domainname . '/webservice/rest/server.php';
        // $data = array(
        //     'wstoken' => "b536dbacaab00ab6924ddd9798a1a611",
        //     'wsfunction' => $functionname,
        //     'courseids[0]' => 2,
        //     'moodlewsrestformat' => 'json'

        // );
        // $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        // print_array($server_output);
    }
    public function test_extraction($_courseid, $token, $book_id, $chapter_id, $date_inserted)
    {
        //   public function get_details_percourse($_courseid, $token, $show = 1)
        $date_inserted_format = date('Y-m-d H:i:s', strtotime($date_inserted));
        $data_analysis = $this->get_details_percourse($_courseid, $token, 0);
        $chaptername = "";
        $modicon = "";
        $contents = "";
        $_page_title = "";
        $stop_search = false;
        $name_levelone = "";
        foreach ($data_analysis as $value_books) {
            // print_array($value_books);
            $name_levelone = $value_books['name'];
            $modules = $value_books['modules'];
            foreach ($modules as  $module) {
                if ($module['instance'] == $book_id) {
                    $chaptername = $module['name'];
                    $modicon = $module['modicon'];
                    $contents = $module['contents'][0]['content'];
                    $contents_array = json_decode($contents, true);
                    foreach ($contents_array as $keyn => $valuen) {
                        if ($valuen['chapter_id'] == $chapter_id) {
                            $_page_title = $valuen['title'];
                            break;
                        }
                    }
                    $stop_search = true;

                    break;
                }
            }
            if ($stop_search) {
                break;
            }
        }
        $array_co_id = array('id' => $_courseid);
        $_courses = array();
        array_push($_courses, $array_co_id);
        $_courses_n = array_value_recursive('id', $_courses);
        $_courses_n_array = $this->get_course_get_courses_by_ids($_courses_n, $token);
        $the_course = array_shift($_courses_n_array);
        $name_course = $the_course['fullname'];
        $name_course_shortname = $the_course['shortname'];
        $name_course_categoryname = $the_course['categoryname'];
        // $name_course_image=$the_course['shortname'];
        $name_course_image_extract = $the_course['overviewfiles'];
        $course_image_get = array_shift($name_course_image_extract);
        $name_course_image = $course_image_get['fileurl'] . '?token=' . $token;
        $array_data = array(
            'name_course' => $name_course,
            'course_shortname' => $name_course_shortname,
            'categoryname' => $name_course_categoryname,
            'name_course_image' => $name_course_image,
            'book_name' => $name_levelone,
            'chaptername' => $chaptername,
            'modicon_chapter' => $modicon,
            'page_title' => $_page_title,
            'date_inserted' => $date_inserted_format
        );
        return $array_data;
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
        print_array($jaja);
    }
    public function getbooksin_course()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'mod_book_get_books_by_courses';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $this->get_admin_token()['token'],
            'wsfunction' => $functionname,
            'courseids[0]' => 2,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $jaja = json_decode($server_output, true);
        // cleanContent
        print_array($jaja);
    }
}
