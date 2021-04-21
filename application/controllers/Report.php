<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");
// ini_set('memory_limit', '2000M');
// libxml_use_internal_errors(true);
use Gaufrette\Filesystem;
use Gaufrette\Adapter\InMemory as InMemoryAdapter;
use Gaufrette\StreamWrapper;

// use JsonMachine\JsonMachine;
// use JsonMachine\JsonDecoder\PassThruDecoder;
// use JsonMachine\JsonDecoder\DecodingError;
// use JsonMachine\JsonDecoder\ErrorWrappingDecoder;
// use JsonMachine\JsonDecoder\ExtJsonDecoder;

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
    public function jaja()
    {
        $startMemory = memory_get_usage();
        $array = new SplFixedArray(100000);
        for ($i = 0; $i < 100000; ++$i) {
            $array[$i] = $i;
        }
        echo memory_get_usage() - $startMemory, ' bytes';
    }
    public function report_surveydetails_old()
    {
        $_POST['selectclientid'] = 2;
        $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        $_POST['startdate'] = "01-02-2021";
        $_POST['enddate'] = "28-02-2021";
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
            $final_array = $this->report_surveydetails_data($persial_survey);
            // echo count($final_array);
            // print_array($final_array);
            // echo json_encode($final_array);
        }
    }
    public function report_surveydetails()
    {
        // $_POST['selectclientid'] = 2;
        // $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        // $_POST['startdate'] = "01-02-2021";
        // $_POST['enddate'] = "28-02-2021";
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
            $final_array = $this->report_surveydetails_data($persial_survey);
            // $table_data['key_bign'] = $bigest_array;
            $major = $final_array[$final_array['key']];
            // $major = $final_array[0];
            //ALL TITELS
            //END ALL
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
                        // $universal_sub['type'] = $tr_data_type[$getkey];
                        $universal_sub['title'] = $valueq;
                        if (array_key_exists('text', $value_in_sub[$getkey])) {
                            if (is_array($value_in_sub[$getkey]['text'])) {
                                $universal_sub['text'] = $value_in_sub[$getkey]['text']['name'];
                            } else {
                                $universal_sub['text'] = $value_in_sub[$getkey]['text'];
                            }
                        } else {
                            $universal_sub['text'] = "No Value";
                        }
                    } else {
                        // $universal_sub['type'] = "";
                        $universal_sub['text'] = "";
                        $universal_sub['title'] = $valueq;
                    }
                    array_push($universal_sub_maj, $universal_sub);
                }
                array_push($universal_values, $universal_sub_maj);
            }
            $alltitlessub = array(
                'username',
                'fullname',
                'submitted date'

            );
            $alltitles = array_merge($alltitlessub, $titles_namesk);
            $table_data['titles'] = $alltitles;
            $table_data['survey_reportdata'] = $universal_values;
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
            //Generate XML
            $arrayexcel = array();
            foreach ($universal_values as $key => $value_excel) {
                $array_one = array(
                    // 'image' => $message,
                    'username' => $value_excel['username'],
                    'fullname' => $value_excel['fullname'],
                    'time_data' => $value_excel['time_data']
                );
                unset_post($value_excel, 'username');
                unset_post($value_excel, 'fullname');
                unset_post($value_excel, 'time_data');
                foreach ($value_excel as $keycatch => $value_catch) {
                    if ($value_catch['title'] !== "html_info") {
                        $array_one[$keycatch] = $value_catch['text'];
                    }
                }
                array_push($arrayexcel, $array_one);
            }
            if (in_array("html_info", $alltitles)) {
                delete_value($alltitles, 'html_info');
            }
            //End Generate XML
            $htmlString = $this->xxxxtimePerClientReport($arrayexcel, $alltitles);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            libxml_use_internal_errors(true);
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'detailswrite.xls');
            echo json_encode($json_return);
        }
    }
    public function report_surveydetails_data($persial_survey)
    {
        $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR;
        $array_object = array();
        foreach ($persial_survey as $key => $value_object) {
            //surveyobject end
            // print_array($value_object['surveyobject']);
            $json_surveyobject = 'surveyobject.json';
            $json_surveyjson = 'surveyjson.json';
            $surveyobjectadapter = new InMemoryAdapter(array($json_surveyobject => $value_object['surveyobject']));
            $surveyjsonadapter = new InMemoryAdapter(array($json_surveyjson => $value_object['surveyjson']));
            $filesystem_surveyobject = new Filesystem($surveyobjectadapter);
            $filesystem_surveyjson = new Filesystem($surveyjsonadapter);
            $map = StreamWrapper::getFilesystemMap();
            $map->set('surveyobject', $filesystem_surveyobject);
            $map->set('surveyjson', $filesystem_surveyjson);
            StreamWrapper::register();
            $surveyobjectpath = $mypath . $json_surveyobject;
            $surveyjsonpath = $mypath . $json_surveyjson;
            copy('gaufrette://surveyobject/surveyobject.json', $surveyobjectpath);
            copy('gaufrette://surveyjson/surveyjson.json', $surveyjsonpath);
            // $surveyobjects = [];
            $surveyobjects = [];
            $parser = new \JsonCollectionParser\Parser();
            $parser->chunk($surveyobjectpath, function (array $chunk) use (&$surveyobjects) {
                // $surveyobjects[] = $item;
                is_array($chunk);    //true
                count($chunk) === 5; //true

                foreach ($chunk as $item) {
                    is_array($item);  //true
                    is_object($item); //false
                    $surveyobjects = $item;
                    // print_array($item);
                }
            }, 5);
            // $parser->parse($surveyjsonpath, function (array $item) use (&$surveyjsons) {
            //     $surveyjsons[] = $item;
            // });
            unlink($surveyobjectpath);
            unlink($surveyjsonpath);
            $arrayn = array(
                'username' => $value_object['id'],
                'fullname' => $value_object['fullname'],
                'submitted_date' => $value_object['dateaddedsurvey'],
                'surveyobject' => $surveyobjects,
                'surveyjson' => json_decode($value_object['surveyjson'], true)
            );
            array_push($array_object, $arrayn);
        }
        // print_array($array_object);
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
                            $arrayc = array(
                                'type' => $valuec['type'],
                                'title' => "html_info",
                                // 'description' => "",
                            );
                            if (array_key_exists('description', $valuec)) {
                                $arrayc['description'] = $valuec['description'];
                            } else {
                                $arrayc['description'] = "";
                            }
                            $value_n = $this->cleanContent($valuec['html']);
                            $arrayc['text'] = $$valuec['html'];
                            $arrayc['value'] = "html_value";
                            array_push($array_of_array, $arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "html") {
                            if (is_array($valuea)) {
                                // print_array($valuea);
                            } else {
                                if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                                    $arrayc = array(
                                        'type' => $valuec['type'],
                                        'title' => "html_info",
                                        'description' => "",
                                    );
                                    $value_n = $this->cleanContent($valuec['html']);
                                    $arrayc['text'] = $valuec['html'];
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
        $persial_survey = $this->universal_model->join_suv_summery($surveyid, $startdate, $enddate);
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
                // $user_details_output = $this->get_meuserdetails($value_datauser['userid']);
                // $jaja_raary = array_shift($user_details_output);
                $user_details = array(
                    'username' => $value_datauser['username'],
                    'fullname' => $value_datauser['fullname'],
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

    function cleanContent($content)
    {
        $content = nl2br($content);
        $content = preg_replace('#(?:<br\s*/?>\s*?){2,}#', ' ', $content);
        return trim(strip_tags($content));
    }
    public function report_perbooks()
    {
        // $_POST['selectclientid'] = 1;
        // $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        $_POST['startdate'] = "12-04-2021";
        $_POST['enddate'] = "22-04-2021";
        // $data['cohorts'] = $this->getme_chort_details();
        // $data['surveys'] = $this->get_surveys();
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->books_reports_time(array('id','user_id', 'he_names', 'name_course', 'course_shortname', 'name_course_image', 'book_name', 'token', 'date_inserted'), $startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For This Book Range",
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            //Modified Data
            $table_data['survey_reportdata'] = $persial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = "Books | Chapters";
            $table_data['table_survey_url'] = 'pages/table/bookschapter_table';
            $json_return = array(
                'report' => "Report For Viewed Books in Range" . $startdate . '  To ' . $enddate,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/book_chaptertemp', $table_data, true),
                'path' => FCPATH . 'excelfiles/'. $this->session->userdata('logged_in_lodda')['id'] .'booksgeneral'. 'write.xls'
            );
            $arrayexcel = $persial_survey;
            delete_value($arrayexcel,'name_course_image');
            // print_array($arrayexcel);
            $ara = array(
                'USERNAME',
                'FULL NAME',
                'COURSE NAME',
                'BOOK NAME',
                'DATE VIEWED',
            );
            print_array($arrayexcel);
            // $htmlString = $this->xxxxtimePerClientReport($arrayexcel, $ara);
            // $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            // $spreadsheet = $reader->loadFromString($htmlString);
            // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            // $writer->save(FCPATH . 'excelfiles/'.$this->session->userdata('logged_in_lodda')['id'] .'booksgeneral'. 'write.xls');
            // echo json_encode($json_return);
        }
    }
}
