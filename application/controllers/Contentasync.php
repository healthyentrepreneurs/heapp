<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");

class Contentasync extends CI_Controller
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
        echo "<h1>Api ContentAsync Api .....</h1>";
    }
    public function syncsurvey($id)
    {
        $_ids_users = $this->getme_cohort_get_cohort_members($id);
        // array_push($_ids_users,37);
        $survey_audit = array();
        foreach ($_ids_users as  $value_id) {
            $value_check = $this->universal_model->selectz('*', 'survey_audit', 'survey_id', $value_id['id']);
            $value_check_clear = array_shift($value_check);
            if (!empty($value_check_clear)) {
                array_push($survey_audit, $value_check_clear);
            }
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
        $updates_survey=array();
        if (!empty($deteled_paths) || !empty($updated_paths)) {
            $_landing_json = array(
                'fileUrl' => base_url('contentasync/surveyjson/') . $id,
                'mode' => 1,
                'localFilePath' => '/get_moodle_courses' . '.json'
            );
            // 
            
            $_what_update=array_merge($forupdate_survey,$what_delete);
            foreach ($_what_update as $value_updasql) {
                $data_copy=array(
                    'user_id'=>$id,
                    'update_id'=>$value_updasql['id'],
                    'update_type'=>'survey',
                    'dateaction'=>$value_updasql['changedat'],
                );
                $this->universal_model->updateOnDuplicate('updatetract', $data_copy);
            }
            $_landing_json_n = array_merge($updated_paths, $deteled_paths);
            array_push($_landing_json_n, $_landing_json);
            $updates_survey=array(
                'description'=>count($_landing_json_n).' Updates',
                'date'=>date('Y-m-d H:i:s'),
                'updates'=>$_landing_json_n
            );
        } else {
            $updates_survey=array(
                'description'=>'0'.' Updates',
                'date'=>date('Y-m-d H:i:s'),
                'updates'=>array()
            );
        }
        echo json_encode($updates_survey);
    }
    public function surveyjson($id)
    {
        $_ids_users = $this->getme_cohort_get_cohort_members($id);
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
        // print_array($new_json_array);
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
        }
        return $array_object;
    }
}
