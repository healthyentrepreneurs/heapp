<?php

use function Amp\Iterator\merge;

defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");
class Cohort extends CI_Controller
{
    public function index($var = null)
    {
        echo "<h1>Api Cohort  .....</h1>";
    }
    // core_cohort_get_cohort_members
    // core_cohort_get_cohorts

    public function getme_chort_details($token)
    {
        // $token = get_admin_token()['token'];
        $functionname = 'core_cohort_get_cohorts';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'cohortids' => array(),

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        return $array_of_output;
        // print_array($array_of_output);
    }

    public function get_cohort_members()
    {
        $token = get_admin_token()['token'];

        $getme_chort_details_output = $this->getme_chort_details($token);
        $array_of_ids = array();
        $array_of_cohorts_ids = array();
        foreach ($getme_chort_details_output as $item) {
            $array_of_ids[] = $item['id'];
            $array_of_cohorts_ids[$item['id']] = $item['idnumber'];
        }
        //Next 
        $functionname = 'core_cohort_get_cohort_members';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'cohortids' => $array_of_ids,
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        $directoryfile = FCPATH . "excelfiles/";
        // Open the CSV file
        $file = fopen($directoryfile . 'namu.csv', 'w');
        // Write the column headers
        // fputcsv($file, array('username', 'firstname', 'lastname', 'email', 'auth', 'lang', 'country', 'cohort1', 'password'));
        fwrite($file, "username,firstname,lastname,email,auth,lang,country,cohort1,password\n");
        foreach ($array_of_output as $value_one) {
            $cohortid = $value_one['cohortid'];
            $userids = $value_one['userids'];
            $get_userdetails_byidarray = $this->get_userdetails_byid($userids, $token);
            if (!array_key_exists('exception', $get_userdetails_byidarray)) {
                foreach ($get_userdetails_byidarray as $value_forcsv) {
                    $value_forcsv['cohort1'] = $array_of_cohorts_ids[$cohortid];
                    $value_forcsv['password'] = 'Newuser123!';
                    if (isset($value_forcsv['firstname'])) {
                        $value_forcsv['firstname'] = str_replace(' ', '%20', trim($value_forcsv['firstname']));
                    }
                    // Trim and replace spaces with %20 in the lastname field
                    if (isset($value_forcsv['lastname'])) {
                        $value_forcsv['lastname'] = str_replace(' ', '%20', trim($value_forcsv['lastname']));
                    }
                    // Write the row directly to the CSV file
                    // fputcsv($file, $value_forcsv);
                    fwrite($file, implode(',', $value_forcsv) . "\n");
                }
            }
        }
        fclose($file);
    }

    public function get_userdetails_byid($id = null, $token)
    {
        $functionname = 'core_user_get_users_by_field';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'field' => 'id',
            'values' => $id

        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        foreach ($array_of_output as $key => $item) {
            if (isset($item['mailformat'])) unset($array_of_output[$key]['mailformat']);
            if (isset($item['profileimageurlsmall'])) unset($array_of_output[$key]['profileimageurlsmall']);
            if (isset($item['profileimageurl'])) unset($array_of_output[$key]['profileimageurl']);
            if (isset($item['preferences'])) unset($array_of_output[$key]['preferences']);
            if (isset($item['confirmed'])) unset($array_of_output[$key]['confirmed']);
            if (isset($item['firstaccess'])) unset($array_of_output[$key]['firstaccess']);
            if (isset($item['lastaccess'])) unset($array_of_output[$key]['lastaccess']);
            if (isset($item['fullname'])) unset($array_of_output[$key]['fullname']);
            if (isset($item['id'])) unset($array_of_output[$key]['id']);
            if (isset($item['department'])) unset($array_of_output[$key]['department']);
            if (isset($item['suspended'])) unset($array_of_output[$key]['suspended']);
            if (isset($item['theme'])) unset($array_of_output[$key]['theme']);
            if (isset($item['timezone'])) unset($array_of_output[$key]['timezone']);
            if (isset($item['city'])) unset($array_of_output[$key]['city']);
            if (isset($item['description'])) unset($array_of_output[$key]['description']);
            if (isset($item['descriptionformat'])) unset($array_of_output[$key]['descriptionformat']);
        }
        return $array_of_output;

        // 
    }
}
