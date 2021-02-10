<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
header('Access-Control-Allow-Origin: *');

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // https://stackoverflow.com/questions/54786609/phpspreadsheet-how-do-i-place-a-image-from-link-into-my-excel-file
        // https://stackoverflow.com/questions/48947078/phpsreadsheet-create-excel-from-html-table
        //Not Over Excell https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/
        //https://stackoverflow.com/questions/64119903/how-can-i-export-html-tables-to-multiple-excel-worksheets-in-php
    }
    public function index()
    {
        echo '<h1>Report Api </h1>';
    }
    public function report_surveydetails()
    {
        $_POST['selectclientid'] = 2;
        $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        // 04-12-2020
        //01-02-2021
        $_POST['startdate'] = "04-12-2020";
        $_POST['enddate'] = "15-12-2020";
        $surveyid = $this->input->post('selectclientid');
        $selectclientname = $this->input->post('selectclientname');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->join_suv_report($surveyid, $startdate, $enddate);
        $final_array = $this->report_surveydetails_data($persial_survey);
        // $table_data['key_bign'] = $bigest_array;
        $table_data['survey_reportdata'] = $final_array;
        $table_data['startdate'] = $startdate;
        $table_data['enddate'] = $enddate;
        $table_data['controller'] = $this;
        $table_data['taskname'] = $selectclientname;
        // The Flesh
        $table_data['table_survey_url'] = 'pages/table/survey_tabledetails';
        $json_return = array(
            'report' => "Report For Survey  in Range" . $selectclientname,
            'status' => 1,
            'data' => $this->load->view('pages/cohort/survey_reportshowtempinfi', $table_data, true),
            'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'detailswrite.xls'
        );
        $major = $final_array[$final_array['key']];
        // $major = $final_array[0];
        unset_post($final_array, 'key');
        unset_post($final_array, 'howbig');
        unset_post($major, 'username');
        unset_post($major, 'fullname');
        unset_post($major, 'submitted_date');
        $titles_namesk = array_column($major, 'title');
        $universal_values = array();
        foreach ($final_array as $keyvalue_in_sub => $value_in_sub) {
            $time_data = $value_in_sub['submitted_date'];
            $username = $value_in_sub['username'];
            $fullname = $value_in_sub['fullname'];
            $universal_sub_maj = array(
                'username' => $username,
                'fullname' => $fullname,
                'time_data' => $time_data

            );
            //Remove 
            unset_post($value_in_sub, 'username');
            unset_post($value_in_sub, 'fullname');
            unset_post($value_in_sub, 'submitted_date');
            $tr_data_type = array_column($value_in_sub, 'type');
            $tr_data_text = array_column($value_in_sub, 'text');
            $tr_data_title = array_column($value_in_sub, 'title');
            foreach ($titles_namesk as $key => $valueq) {
                $universal_sub = array();
                if (in_array($valueq, $tr_data_title, TRUE)) {
                    $getkey = array_search($valueq, $tr_data_title, true);
                    print_array($tr_data_text);
                    print_array($getkey);
                    print_array('.................<br>');
                    $universal_sub['type'] = $tr_data_type[$getkey];
                    // $universal_sub['text'] = $tr_data_text[$getkeytext];
                    $universal_sub['title'] = $valueq;
                } else {
                    $universal_sub['type'] = "";
                    $universal_sub['text'] = "";
                    $universal_sub['title'] = $valueq;
                }
                array_push($universal_sub_maj, $universal_sub);
            }
            array_push($universal_values, $universal_sub_maj);
        }
        // print_array($universal_values);
        // echo json_encode($json_return);
    }
    public function report_surveydetails_data($persial_survey)
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
                                    $string_values_mama .= " , " . $value_n['text'];
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
                                $arrayc['text'] = $string_values_mama;
                                $arrayc['value'] = $valuea[0];
                                // print_array($string_values_mama);
                                array_push($array_of_array, $arrayc);
                            }
                        }
                        if ($valuec['type'] == "html" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            $arrayc = array(
                                'type' => $valuec['type'],
                                'title' => "html_value",
                                // 'description' => "",
                            );
                            if (array_key_exists('description', $valuec)) {
                                $arrayc['description'] = $valuec['description'];
                            } else {
                                $arrayc['description'] = "";
                            }
                            $value_n = $valuec['html'];
                            $arrayc['text'] = $value_n;
                            $arrayc['value'] = "html_value";
                            array_push($array_of_array, $arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "html") {
                            if (is_array($valuea)) {
                                // print_array($valuea);
                            } else {
                                if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                                    $arrayc = array(
                                        'type' => $valuec['type'],
                                        'title' => "html_value",
                                        'description' => "",
                                    );
                                    $value_n = $valuec['html'];
                                    $arrayc['text'] = $value_n;
                                    $arrayc['value'] = "html_value";
                                    array_push($array_of_array, $arrayc);
                                }
                            }
                        }
                        //Start Test
                        if ($valuec['type'] == "text" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            // print_array($keya);
                            $arrayc = array(
                                'type' => $valuec['type'],
                                'title' => $valuec['title'],
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
                            $jaja_image = array_shift($surveyobject[$keya]);
                            //End Tricky
                            $arrayc['text'] = $jaja_image;
                            $arrayc['value'] = $keya;
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
                                $jaja_image = array_shift($surveyobject[$keya]);
                                //End Tricky
                                $arrayc['text'] = $jaja_image;
                                $arrayc['value'] = $keya;
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
    public function report_survey()
    {
        // $_POST['selectclientid'] = 1;
        // $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        // $_POST['startdate'] = "01-01-2021";
        // $_POST['enddate'] = "31-02-2021";
        // $data['cohorts'] = $this->getme_chort_details();
        // $data['surveys'] = $this->get_surveys();
        $surveyid = $this->input->post('selectclientid');
        $selectclientname = $this->input->post('selectclientname');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->join_suv_report($surveyid, $startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For This Survey Combination",
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            $modipersial_survey = array();
            foreach ($persial_survey as $key => $value_datauser) {
                // $call_details_url = base_url('user/get_meuserdetails/' . $value_datauser['userid']);
                $user_details_output = $this->get_meuserdetails($value_datauser['userid']);
                $jaja_raary = array_shift($user_details_output);
                $user_details = array(
                    'username' => $jaja_raary['username'],
                    'fullname' => $jaja_raary['fullname'],
                );
                $mergerdata = array_merge($user_details, $value_datauser);
                array_push($modipersial_survey, $mergerdata);
                // print_array($user_details);
            }
            //Modified Data
            $table_data['survey_reportdata'] = $modipersial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = $selectclientname;
            // $table_data['table_survey'] = $this->load->view('pages/table/survey_table', $table_data, true);
            $table_data['table_survey_url'] = 'pages/table/survey_table';
            $json_return = array(
                'report' => "Report For Survey  in Range" . $selectclientname,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/survey_reportshowtemp', $table_data, true),
                'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'write.xls'
            );
            $arrayexcel = array();
            foreach ($modipersial_survey as $key => $value_excel) {
                $time_now = explode(" ", $value_excel['dateaddedsurvey']);
                $array_one = array(
                    // 'image' => $message,
                    'username' => $value_excel['username'],
                    'fullname' => $value_excel['fullname'],
                    'name' => $value_excel['name'],
                    'surveydesc' => $value_excel['surveydesc'],
                    'dateaddedsurvey' => $time_now[0],
                    'time' => $time_now[1],
                );
                array_push($arrayexcel, $array_one);
            }
            // print_array($arrayexcel);
            $ara = array(
                'USERNAME',
                'FULL NAME',
                'SURVEY NAME',
                'SURVEY SUM',
                'DATE SUBMITTED',
                'TIME SUBMITTED'
            );
            $htmlString = $this->xxxxtimePerClientReport($arrayexcel, $ara);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'write.xls');
            echo json_encode($json_return);
        }
    }
    public function xxxxtimePerClientReport($data, $ara)
    {
        // $this->table->set_heading('IMAGE', 'SURVEY NAME', 'DATE CREATED', 'WHO SUBMITTED');
        // $this->table->set_heading(array('Name', 'Color', 'Size'));

        $this->table->set_heading($ara);
        // $this->table->set_heading('USERNAME', 'FULL NAME', 'SURVEY NAME', 'DATE SUBMITTED', 'TIME SUBMITTED');
        return $this->table->generate($data);
    }
    public function get_meuserdetails($user_id)
    {
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = 'f84bf33b56e86a4664284d8a3dfb5280';
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
    public function detailsurvey($id, $idsurv)
    {
        // $id = $this->input->get('id');
        // $idsurv = $this->input->get('idsurv');
        $report_data = $this->universal_model->join_suv_report_details($idsurv, $id);
        if (empty($report_data)) {
            return array();
        } else {
            $report_data_n = array_shift($report_data);
            $surveyjson = $report_data_n['surveyjson'];
            $surveyjson_array = json_decode($surveyjson, true);
            $survey_responsejson = $report_data_n['surveyobject'];
            $response_array = json_decode($survey_responsejson, true);
            $array_table_values = array();
            foreach ($surveyjson_array['pages'] as $key => $value) {
                //    print_array($value);
                $element_analy = $value['elements'];
                foreach ($element_analy as $key => $value_elem) {
                    if (key_exists($value_elem['name'], $response_array)) {
                        $get_value = $response_array[$value_elem['name']];
                        // print_array($get_value);
                        if (key_exists('description', $value_elem)) {
                            $desc = $value_elem['description'];
                        } else {
                            $desc = "";
                        }
                        $value_baby = array(
                            'title' => $value_elem['title'],
                            'description' => $desc
                        );
                        if ($value_elem['type'] == "text") {
                            $value_baby['value_score'] = $get_value;
                            $value_baby['type'] = $value_elem['type'];
                        } else if ($value_elem['type'] == "radiogroup") {
                            $value_elem_choice = $value_elem['choices'];
                            foreach ($value_elem_choice as $key_choice => $value_choice) {
                                if ($value_choice['value'] == $get_value) {
                                    $value_baby['value_score'] = $value_choice['text'];
                                    $value_baby['type'] = $value_elem['type'];
                                }
                            }
                        } else if ($value_elem['type'] == "checkbox") {
                            $value_elem_choice = $value_elem['choices'];
                            foreach ($value_elem_choice as $key_choice => $value_choice) {
                                if ($value_choice['value'] == $get_value) {
                                    $value_baby['value_score'] = $value_choice['text'];
                                    $value_baby['type'] = $value_elem['type'];
                                }
                            }
                        } else if ($value_elem['type'] == "html") {
                            $value_baby['value_score'] = $value_elem['html'];
                            $value_baby['type'] = $value_elem['type'];
                        } else if ($value_elem['type'] == "file") {
                            $attempt_n_n_one = $this->universal_model->selectzy('imageifany', 'survey_report', 'id', $id, 'imageifany', "none");
                            if (!empty($attempt_n_n_one)) {
                                if (!empty($get_value)) {
                                    $get_value = array_shift($get_value);
                                    $name_final = getToken(10) . $get_value['name'];
                                    $one = $get_value['content'];
                                    $two = str_replace("data:image/jpeg;base64,", "", $one);
                                    // data:image/jpeg;base64,
                                    // $value_baby['image_base_obj'] = $two;
                                    $value_baby['value_score'] = $name_final;
                                    $value_baby['type'] = $value_elem['type'];
                                    $path = FCPATH . "uploadsurvey/" . $name_final;
                                    $status = file_put_contents($path, base64_decode($two));
                                    if ($status) {
                                        // public function updatez($variable, $value, $table_name, $updated_values)
                                        $this->universal_model->updatez("id", $id, "survey_report", array('imageifany' => $name_final));
                                    }
                                } else {
                                    $value_baby['value_name'] = "";
                                }
                            } else {
                                $attempt_n_n_one = $this->universal_model->selectz('imageifany', 'survey_report', 'id', $id);
                                $array_one = array_shift($attempt_n_n_one);
                                $value_baby['value_score'] = $array_one['imageifany'];
                                $value_baby['type'] = $value_elem['type'];
                            }
                        } else {
                            // print_array($value_elem);
                            $value_baby['value_score'] = $value_elem['What element is this?'];
                            $value_baby['type'] = $value_elem['type'];
                        }
                        // print_array($value_baby);
                        array_push($array_table_values, $value_baby);
                    }
                }
            }
            return $array_table_values;
        }
        // print_array($array_table_values);
    }
    // function createPhoneNumber(array $numbersarray): string
    // {
    //     return sprintf("(%d%d%d) %d%d%d-%d%d%d%d", ...$numbersarray);
    // }
    // public function testme()
    // {
    //     $array_name = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
    //     $stringname = $this->createPhoneNumber($array_name);
    //     print_array($stringname);
    // }
}
