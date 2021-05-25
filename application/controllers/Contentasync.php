             <?php
                defined('BASEPATH') or exit('No direct script access allowed');
                // header('Access-Control-Allow-Origin: *');
                date_default_timezone_set("Africa/Nairobi");
                require_once FCPATH . 'vendor/autoload.php';
                // https://www.codegrepper.com/code-examples/whatever/codeigniter+asynchronous-processing

                class Contentasync extends CI_Controller
                {
                    public function __construct()
                    {
                        parent::__construct();
                        $this->load->model('universal_model');
                        $this->load->model('user_model', '', TRUE);
                        // $this->load->library('asynclibrary');
                    }
                    public function index($var = null)
                    {
                        echo "<h1>Api ContentAsync Api .....</h1>";
                    }

                    public function syncsurvey($id)
                    {
                        $_ids_users = $this->getme_cohts($id);
                        // array_push($_ids_users,37);
                        $survey_audit = array();
                        foreach ($_ids_users as  $value_id) {
                            $value_check = $this->universal_model->selectz('*', 'survey_audit', 'survey_id', $value_id['id']);
                            $value_check_clear = array_shift($value_check);
                            array_push($survey_audit, $value_check_clear);
                            // if (!empty($value_check_clear)) {
                            //     array_push($survey_audit, $value_check_clear);
                            // }
                        }
                        $forupdate_survey = array();
                        $what_delete = array();
                        foreach ($survey_audit as $valuen) {
                            $value_check = $this->universal_model->selectzxppp('*', 'updatetract', 'update_id', $valuen['id'], 'user_id', $id, 'update_type', 'survey', 'dateaction', $valuen['changedat']);
                            if (empty($value_check)) {
                                if ($valuen['action'] == 'deleted') {
                                    array_push($what_delete, $valuen);
                                } else {
                                    array_push($forupdate_survey, $valuen);
                                }
                            }
                        }
                        $updated_paths = array();
                        foreach ($forupdate_survey as  $valueup_path) {
                            $value_check = $this->universal_model->selectz(array('image_url_small', 'image', 'id'), 'survey', 'id', $valueup_path['survey_id']);
                            if (!empty($value_check)) {
                                $value_check_n = array_shift($value_check);
                                // print_array($value_check_n);
                                $imagebig_name = explode('/', $value_check_n['image']);
                                $imagesmall_name = explode('/', $value_check_n['image_url_small']);
                                $now_val_big = array(
                                    'fileUrl' => base_url('uploadscustome/') . $imagebig_name[count($imagebig_name) - 1],
                                    'mode' => 1,
                                    'localFilePath' => '/images/survey/' . $imagebig_name[count($imagebig_name) - 1]
                                );
                                $now_val_small = array(
                                    'fileUrl' => base_url('uploadscustome/') . $imagesmall_name[count($imagesmall_name) - 1],
                                    'mode' => 1,
                                    'localFilePath' => '/images/survey/' . $imagesmall_name[count($imagesmall_name) - 1]
                                );
                                $now_val_json = array(
                                    'fileUrl' => base_url('survey/getnexlink/') . $value_check_n['id'],
                                    'mode' => 1,
                                    'localFilePath' => '/next_link/survey/' . $value_check_n['id'] . '.json'
                                );
                                array_push($updated_paths, $now_val_big);
                                array_push($updated_paths, $now_val_small);
                                array_push($updated_paths, $now_val_json);
                            }
                        }
                        $deteled_paths = array();
                        foreach ($what_delete as $value_del_path) {
                            $value_check = $this->universal_model->selectz(array('image_url_small', 'image', 'survey_id'), 'survey_deleted', 'survey_id', $value_del_path['survey_id']);
                            if (!empty($value_check)) {
                                $value_check_n = array_shift($value_check);
                                // print_array($value_check_n);
                                $imagebig_name = explode('/', $value_check_n['image']);
                                $imagesmall_name = explode('/', $value_check_n['image_url_small']);
                                $now_val_big = array(
                                    'mode' => 0,
                                    'localFilePath' => '/images/survey/' . $imagebig_name[count($imagebig_name) - 1]
                                );
                                $now_val_small = array(
                                    'mode' => 0,
                                    'localFilePath' => '/images/survey/' . $imagesmall_name[count($imagesmall_name) - 1]
                                );
                                $now_val_json = array(
                                    'mode' => 0,
                                    'localFilePath' => '/next_link/survey/' . $value_check_n['survey_id'] . '.json'
                                );
                                array_push($deteled_paths, $now_val_big);
                                array_push($deteled_paths, $now_val_small);
                                array_push($deteled_paths, $now_val_json);
                            }
                        }
                        $updates_survey = array();
                        if (!empty($deteled_paths) || !empty($updated_paths)) {
                            $_landing_json = array(
                                'fileUrl' => base_url('contentasync/surveyjson/') . $id,
                                'mode' => 1,
                                'localFilePath' => '/get_moodle_courses' . '.json'
                            );
                            // 

                            $_what_update = array_merge($forupdate_survey, $what_delete);
                            foreach ($_what_update as $value_updasql) {
                                $data_copy = array(
                                    'user_id' => $id,
                                    'update_id' => $value_updasql['id'],
                                    'update_type' => 'survey',
                                    'dateaction' => $value_updasql['changedat'],
                                );
                                $this->universal_model->updateOnDuplicate('updatetract', $data_copy);
                            }
                            $_landing_json_n = array_merge($updated_paths, $deteled_paths);
                            array_push($_landing_json_n, $_landing_json);
                            $updates_survey = array(
                                'description' => count($_landing_json_n) . ' Updates',
                                'date' => date('Y-m-d H:i:s'),
                                'updates' => $_landing_json_n
                            );
                        } else {
                            $updates_survey = array(
                                'description' => '0' . ' Updates',
                                'date' => date('Y-m-d H:i:s'),
                                'updates' => array()
                            );
                        }
                        echo json_encode($updates_survey);
                    }
                    public function syncbooks($id, $token = null)
                    {
                        $_queried = $this->getme_books($token, $id);
                        //Now We Start
                        $books_audit = array();
                        foreach ($_queried as  $value_id) {
                            #URL getbookcourse_id
                            $value_check = $this->getbookcourse_id($value_id['id']);
                            if (!array_key_exists('code', $value_check)) {
                                $books_audit = array_merge($books_audit, $value_check);
                                // array_push($books_audit, $value_check_clear);
                            }
                        }
                        $forupdate_book = array();
                        $what_delete = array();
                        foreach ($books_audit as $valuen) {
                            $value_check = $this->universal_model->selectzxppp('*', 'updatetract', 'update_id', $valuen['id'], 'user_id', $id, 'update_type', 'book', 'dateaction', $valuen['changedat']);
                            if (empty($value_check)) {
                                if ($valuen['action'] == 'deleted') {
                                    array_push($what_delete, $valuen);
                                } else {
                                    array_push($forupdate_book, $valuen);
                                }
                            }
                        }
                        $updated_paths = array();
                        $course_update_one = array();
                        $download_books = array();;
                        foreach ($forupdate_book as  $valueup_path) {
                            if (!in_array($valueup_path['course_id'], $course_update_one)) {
                                $now_val_json = array(
                                    'fileUrl' => base_url('downloadable/book_course/') . $id . '/' . $token . '/' . $valueup_path['course_id'],
                                    'mode' => 1,
                                    'localFilePath' => '/next_link/get_details_percourse/' . $valueup_path['course_id'] . '.json'
                                );
                                array_push($updated_paths, $now_val_json);
                                array_push($course_update_one, $valueup_path['course_id']);
                            }
                            $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'next_link/get_details_percourse/' . $valueup_path['course_id'] . DIRECTORY_SEPARATOR . $valueup_path['book_id'];
                            if (file_exists($mypath)) {
                                array_push($download_books, $mypath);
                            }
                        }
                        $deteled_paths = array();
                        foreach ($what_delete as $value_del_path) {
                            $now_val_json = array(
                                'mode' => 0,
                                'localFilePath' => '/next_link/get_details_percourse/' . $value_del_path['course_id'] . '/' . $value_del_path['book_id'] . '.json'
                            );
                            array_push($deteled_paths, $now_val_json);
                        }
                        $updates_books = array();
                        $_what_update = array_merge($forupdate_book, $what_delete);
                        if (!empty($_what_update)) {
                            foreach ($_what_update as $value_updasql) {
                                $data_copy = array(
                                    'user_id' => $id,
                                    'update_id' => $value_updasql['id'],
                                    'update_type' => 'book',
                                    'dateaction' => $value_updasql['changedat'],
                                );
                                $this->universal_model->updateOnDuplicate('updatetract', $data_copy);
                            }
                            $_landing_json_n = array_merge($updated_paths, $deteled_paths);
                            $updates_books = array(
                                'description' => count($_landing_json_n) . ' Updates',
                                'date' => date('Y-m-d H:i:s'),
                                'updates' => $_landing_json_n
                            );
                        } else {
                            $updates_books = array(
                                'description' => '0' . ' Updates',
                                'date' => date('Y-m-d H:i:s'),
                                'updates' => array()
                            );
                        }
                        // ->add(function () use ($download_books) {
                        //     $domainname = base_url('downloadable/create_content/');
                        //     $post_params = array(
                        //         'cohort_object' => '1@mega'
                        //     );
                        //     // echo $domainname;
                        //     $server_output = curl_request($domainname, $post_params, "post", array('App-Key: 123456'));
                        //     $array_of_output = json_decode($server_output, true);
                        //     return $array_of_output;
                        // })->then(function ($array_of_output) {
                        //     print_array($array_of_output);
                        // })

                        // print_array($download_books);

                        // download_books
                        echo json_encode($updates_books);
                    }
                    public function surveyjson($id)
                    {
                        header('Content-Type: application/json');
                        $_ids_users = $this->getme_cohts($id);
                        $new_json_array = array();
                        foreach ($_ids_users as $value_land) {
                            $imagebig_name = explode('/', $value_land['image_url']);
                            $imagesmall_name = explode('/', $value_land['image_url_small']);
                            $value_land['next_link'] = '/next_link/survey/' . $value_land['id'] . '.json';
                            $value_land['image_url'] = '/images/survey/' . $imagebig_name[count($imagebig_name) - 1];
                            $value_land['image_url_small'] = '/images/survey/' . $imagesmall_name[count($imagesmall_name) - 1];
                            array_push($new_json_array, $value_land);
                        }
                        echo json_encode($new_json_array);
                    }
                    public function get_admin_token()
                    {
                        $domainname = 'https://app.healthyentrepreneurs.nl/login/token.php?username=mega&password=Mega1java123!@%23&service=addusers';
                        $data = array();
                        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
                        $array_of_output = json_decode($server_output, true);
                        // print_array($array_of_output);
                        return $array_of_output;
                    }
                    public function getme_cohts($id)
                    {
                        $domainname = base_url('user/getme_cohort_get_cohort_members/') . $id . '/' . '1';
                        $server_output = curl_request($domainname, array(), "get", array('App-Key: 123456'));
                        $array_of_output = json_decode($server_output, true);
                        return $array_of_output;
                    }

                    public function getme_books($token, $user_id)
                    {
                        // public function _get_moodle_course_inter($token = "de81bb4eb4e8303a15b00a5c61554e2a", $user_id = 3)
                        $domainname = base_url('user/get_moodle_course_inter/') . $token . '/' . $user_id . '/1';
                        // echo $domainname;
                        $server_output = curl_request($domainname, array(), "get", array('App-Key: 123456'));
                        $array_of_output = json_decode($server_output, true);
                        return $array_of_output;
                    }

                    public function getbookcourse_id($course_id)
                    {
                        // public function _get_moodle_course_inter($token = "de81bb4eb4e8303a15b00a5c61554e2a", $user_id = 3)
                        $domainname = 'https://app.healthyentrepreneurs.nl/moodleapi/api/getbookcourse_id/' . $course_id;
                        // echo $domainname;
                        $server_output = curl_request($domainname, array(), "get", array('App-Key: 123456'));
                        $array_of_output = json_decode($server_output, true);
                        return $array_of_output;
                    }

                    public function update_ng_xx()
                    {
                      $this->load->library('backgroundprocess');
                       $proc=new BackgroundProcess('exec php /var/www/html/index.php Downloadable book_download');
                       echo "joshua";
                    }
                    public function update_ng()
                    {
                        $urlArray = array(
                            'https://helper.healthyentrepreneurs.nl/downloadable/book_download',
                            'https://helper.healthyentrepreneurs.nl/downloadable/create_content/',
                            'https://helper.healthyentrepreneurs.nl/contentasync/syncbooks/3/2cedf0d2bd87e32db7e9b57fc6ec9a34'
                        );
                        $this->load->library('backgroundprocess');
                        // $this->backgroundprocess->setCmd('php https://helper.healthyentrepreneurs.nl/downloadable/book_download');
                        $this->backgroundprocess->setCmd("curl -o /www/application/logs/log_background_process.log " . base_url('downloadable/create_content'));
                        $this->backgroundprocess->start();
                        $pid = $this->backgroundprocess->getProcessId();
                        // echo $this->backgroundprocess->get_log_paths();
                        // echo $pid . "\n";
                        print_array($this->update_ng_xx());
                    }
                }

        // https://github.com/llagerlof/MoodleRest/wiki/MoodleRest-examples
