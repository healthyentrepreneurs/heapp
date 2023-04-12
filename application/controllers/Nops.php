<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");
require_once FCPATH . 'vendor/autoload.php';
class Nops extends CI_Controller
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
    public function login()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = str_replace(' ', '', $this->input->post('username'));
            $password = str_replace('#', '%23', $this->input->post('password'));
            // $password = $this->input->post('password');
            $serverurl = MOODLEAPP_DOMAIN . '/login/token.php';
            $data = array(
                'username' => $username,
                'password' => $password,
                'service' => 'moodle_mobile_app'
            );
            $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            if (array_key_exists('exception', $array_of_output)) {
                echo empty_response(strip_tags($array_of_output['message']), 400, []);
            } else {
                if (array_key_exists('errorcode', $array_of_output)) {
                    echo empty_response("User not found / Wrong Credentials", 400, []);
                } else {
                    $details_user = $this->get_userdetails_internal($username);
                    $token_details = array_merge($array_of_output, $details_user[0]);
                    $data = array(
                        'id_id' => $details_user[0]['id'],
                        'username' => $username,
                        'password' => $this->input->post('password'),
                    );
                    $value_check = $this->universal_model->updateOnDuplicate('user', $data);
                    echo empty_response("successfully logged in", 200, $token_details);
                }
            }
        } else {
            echo empty_response("Credentials Are Required", 500, []);
        }
    }
    public function get_userdetails_internal($username = null)
    {
        $token =  $token = get_admin_token()['token'];
        $functionname = 'core_user_get_users_by_field';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'username',
            'values[0]' => $username

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function get_admin_token()
    {
        $domainname = MOODLEAPP_DOMAIN . MOODLEAPP_ADMIN;
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
    }
    public function synchapter()
    {
        // $items = Array(523,3452,334,31,5346);
        // echo $items[array_rand($items)];
        $chapter_books = $this->universal_model->selectmissingchapsuni(array('course_id', 'book_id'));
        $domainname = MOODAPI . '/api/get_all_chaptersbybook/';
        $update_array_chapteridtitle = array();
        $delete_array_chapteridtitle = array();
        foreach ($chapter_books as $key => $valuechap) {
            $case_domainname = $domainname . '' . $valuechap['book_id'];
            $server_output = curl_request($case_domainname, array(), "get", array('App-Key: 123456'));
            $array_of_output = json_decode($server_output, true);
            $keys_chapid = array_keys($array_of_output);
            if (!empty($keys_chapid)) {
                // print_array($keys_chapid);
                $update_chapter_books = $this->universal_model->selectzy(array('id', 'book_id', 'view_id', 'chaptername'), 'viewtable', 'view_id', "", "book_id", $valuechap['book_id']);
                foreach ($update_chapter_books  as $updatetitleid) {
                    $chapterid = $keys_chapid[array_rand($keys_chapid)];
                    $chaptertitle = $array_of_output[$chapterid];
                    $inner_chapterup = array(
                        'id' => $updatetitleid['id'],
                        'view_id' => $chapterid,
                        'chaptername' => $chaptertitle,
                    );
                    $update_array_chapteridtitle[] = $inner_chapterup;
                    // echo $chaptertitle;
                    // print_array($inner_chapterup);
                    // echo '<br>';
                }
            } else {
                // $this->universal_model->deletez('viewtable', 'id',);
                $clean_thus = $this->universal_model->selectz(array('id', 'book_id', 'course_id', 'date_inserted'), 'viewtable', 'book_id', $valuechap['book_id']);
                //Control Mass Deletes
                if (!empty($clean_thus)) {
                    // $this->universal_model->deletez('viewtable', 'id',);
                    foreach ($clean_thus  as $deletetitleid) {
                        $inner_chaptedelete = array(
                            'course_id' => $deletetitleid['course_id'],
                            'chapter_id' => $deletetitleid['id'],
                            'book_id' => $deletetitleid['book_id'],
                            'created_at' => $deletetitleid['date_inserted'],
                        );
                        $delete_array_chapteridtitle[] = $inner_chaptedelete;
                        $this->universal_model->deletez('viewtable', 'id', $deletetitleid['id']);
                        // echo $chaptertitle;
                        // print_array($inner_chapterup);
                        // echo '<br>';
                    }
                }
                // $this->universal_model->deletez('viewtable', 'id',);
            }
        }
        $messag_je = array();
        if (!empty($update_array_chapteridtitle)) {
            $this->db->update_batch('viewtable', $update_array_chapteridtitle, 'id');
            $messag_je[] = array('count' => count($update_array_chapteridtitle), 'message' => "Sync Successfully");
        }
        if (!empty($delete_array_chapteridtitle)) {
            // $this->db->update_batch('bookchaptermissing', $update_array_chapteridtitle, 'id');
            $this->db->insert_batch('bookchaptermissing', $delete_array_chapteridtitle);
            // $this->universal_model->updateOnDuplicate('bookchaptermissing', $delete_array_chapteridtitle);
            $messag_je[] = array('count' => count($delete_array_chapteridtitle), 'message' => "Deleted Successfully");
        }
        // $this->db->update_batch('viewtable', $update_array_chapteridtitle, 'id');
        echo json_encode($messag_je);
    }
    public function synbook()
    {
        $missing_name_books = $this->universal_model->selectmissingbooksuni(array('id', 'course_id', 'book_id'));
        $bookname = MOODAPI . '/api/getbookname/';
        $update_array_booknam = array();
        $none_book = array();
        foreach ($missing_name_books as  $valuechap) {
            $case_domainname = $bookname . '' . $valuechap['book_id'];
            $server_output = curl_request($case_domainname, array(), "get", array('App-Key: 123456'));
            $array_name_output = json_decode($server_output, true);
            if (!empty($array_name_output)) {
                $name = array_shift($array_name_output)['name'];
                $update_this_book = $this->universal_model->selectzy(array('id'), 'viewtable', 'book_name', "", "book_id", $valuechap['book_id']);
                foreach ($update_this_book as  $bookidnow) {
                    $inner_chapterup = array(
                        'id' => $bookidnow['id'],
                        'book_name' => $name,
                        'modicon_chapter' => "https://helper.healthyentrepreneurs.nl/uploadicons/600_user_profile_picZVD.png",
                    );
                    $update_array_booknam[] = $inner_chapterup;
                }
            } else {
                $none_book[] = $case_domainname;
            }
        }
        $messag_je = array();
        if (!empty($update_array_booknam)) {
            $this->db->update_batch('viewtable', $update_array_booknam, 'id');
            $messag_je[] = array('count' => count($update_array_booknam), 'message' => "Sync Successfully");
        }
        if (!empty($none_book)) {
            // $this->universal_model->updateOnDuplicate('bookchaptermissing', $delete_array_chapteridtitle);
            $messag_je[] = array('count' => count($none_book), 'message' => "Deleted Successfully");
        }
        echo json_encode($messag_je);
    }

    public function datagenerate()
    {
        // $date_array = getDatesFromRange('15-12-2021', '31-12-2021');
        // FAPPATH
        $filecsv = FCPATH . 'uploads_clientapp/output.sql';
        // $csv = array_map('str_getcsv', file($filecsv));
        // array_shift($csv);
        // foreach ($csv as $keycsv => $valuecsv) {
        //     $stringanod=$valuecsv[$keycsv];
        //     // $parts = preg_split('/\s+/', $stringanod);
        //     $parts=preg_split('/\s+/', $stringanod, -1, PREG_SPLIT_NO_EMPTY);
        //     print_array($parts);
        //     break;
        // }
        // echo $date_array[array_rand($date_array)];
        // $rows   = array_map('str_getcsv', file($filecsv));
        // $header = array_shift($rows);
        // $csv    = array();
        // foreach ($rows as $row) {
        //     $csv[] = array_combine($header, $row);
        // }
        // print_array($csv);
        $rownum = 0;
        $importarray = array_shift($rows);
        while (($row = fgetcsv($filecsv, 1000, ',')) !== FALSE) {
            if ($rownum > 0) {
                $row = array_combine($importarray[0], $row);
            }
            array_push($importarray, $row);
            $rownum++;
        }
        array_shift($importarray);
    }
}

// https://phpmyadmin-dot-he-test-server.uc.r.appspot.com/index.php
