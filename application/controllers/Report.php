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
    public function report_surveydetails_temp()
    {
        $_POST['selectclientid'] = 41;
        $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        $_POST['startdate'] = "01-10-2021";
        $_POST['enddate'] = "15-10-2021";
        $surveyid = $this->input->post('selectclientid');
        $selectclientname = $this->input->post('selectclientname');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->join_suv_report($surveyid, $startdate, $enddate);
        $final_array = $this->report_surveydetails_data($persial_survey);
        print_array($final_array);
        //    echo json_encode($persial_survey);
    }
    public function report_surveydetails()
    {
        //Sample Data 
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
                'username' => $value_object['username'],
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
                            // $arrayc = array(
                            //     'type' => $valuec['type'],
                            //     'title' => "html_info",
                            //     // 'description' => "",
                            // );
                            // if (array_key_exists('description', $valuec)) {
                            //     $arrayc['description'] = $valuec['description'];
                            // } else {
                            //     $arrayc['description'] = "";
                            // }
                            // $value_n = cleanContent($valuec['html']);
                            // $arrayc['text'] = $valuec['html'];
                            // $arrayc['value'] = "html_value";
                            // array_push($array_of_array, $arrayc);
                        } elseif (array_key_exists('visibleIf', $valuec) && $valuec['type'] == "html") {
                            // if (is_array($valuea)) {
                            //     // print_array($valuea);
                            // } else {
                            //     if (strpos($valuec['visibleIf'], $keya) == true && strpos($valuec['visibleIf'], $valuea) == true) {
                            //         $arrayc = array(
                            //             'type' => $valuec['type'],
                            //             'title' => "html_info",
                            //             'description' => "",
                            //         );
                            //         $value_n = cleanContent($valuec['html']);
                            //         $arrayc['text'] = $valuec['html'];
                            //         $arrayc['value'] = "html_value";
                            //         array_push($array_of_array, $arrayc);
                            //     }
                            // }
                        }
                        //Start Test
                        if ($valuec['type'] == "text" && $valuec['name'] == $keya && !array_key_exists('visibleIf', $valuec)) {
                            print_array($valuec);
                            $arrayc = array(
                                'type' => $valuec['type'],
                                // 'title' => $valuec['title'],
                            );
                            if (array_key_exists('title', $valuec)) {
                                $arrayc['title'] = $valuec['title'];
                            }
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
                            print_array("joash");
                            print_array($valuec);
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
                                $key_value = trim($valuec['name']);
                                // print_array($key_value);
                                #Njovu New Addition Thurs 8th July 2021 17:36
                                if (array_key_exists($key_value, $surveyobject)) {
                                    $arrayc['text'] = $surveyobject[$key_value];
                                    $arrayc['value'] = $keya;
                                    array_push($array_of_array, $arrayc);
                                }
                            }
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


    public function report_perbooks()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->sum_book_data($startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For This Book",
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            //Modified Data
            $table_data['survey_reportdata'] = $persial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = "Summery Book Report";
            $table_data['table_survey_url'] = 'pages/table/bookschapter_table';
            $json_return = array(
                'report' => "Report in Range" . $startdate . '  To ' . $enddate,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/book_chaptertemp', $table_data, true),
                'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'booksgeneral' . 'write.xls'
            );
            $ara = array(
                'BOOK NAME',
                'COURSE',
                'BOOKS VIEWED',
                'CHAPTERS VIEWED',
                'UNIQUE USERS VIEWED'
            );
            $htmlString = $this->xxxxtimePerClientReport($persial_survey, $ara);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'booksgeneral' . 'write.xls');
            echo json_encode($json_return);
        }
    }

    public function books_reportdetails()
    {
        $courseid = $this->input->post('courseid');
        $bookid = $this->input->post('bookid');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->books_reports_chapter(array('he_names', 'user_id', 'course_shortname', 'book_name', 'chaptername', 'modicon_chapter', 'date_inserted'), $startdate, $enddate, $courseid, $bookid);
        // echo json_encode($_POST);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For This Range",
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
            $table_data['table_survey_url'] = 'pages/table/chapterchapter_table';
            $json_return = array(
                'report' => "Report For Viewed Books in Range" . $startdate . '  To ' . $enddate,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/chapter_chaptertemp', $table_data, true),
                'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'chapter' . 'write.xls'
            );
            $arrayexcel = array();
            foreach ($persial_survey as $key => $value_header) {
                unset_post($value_header, 'modicon_chapter');
                array_push($arrayexcel, $value_header);
            }
            $ara = array(
                'FULL NAME',
                'USERNAME',
                'COURSE',
                'BOOK NAME',
                'CHAPTER',
                'DATE VIEWED',
            );
            $htmlString = $this->xxxxtimePerClientReport($arrayexcel, $ara);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'chapter' . 'write.xls');
            echo json_encode($json_return);
        }
    }
    public function sum_user_report()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->sum_user_data($startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For Summary Users",
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            //Modified Data
            $table_data['survey_reportdata'] = $persial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = "Summery User Report In Range " . $startdate . ' To ' . $enddate;
            $table_data['table_survey_url'] = 'pages/table/sumuser_table';
            $json_return = array(
                'report' => "Report in Range" . $startdate . '  To ' . $enddate,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/sumuser_temp', $table_data, true),
                'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'sumusers' . 'write.xls'
            );
            $ara = array(
                'FULL NAME',
                'USERNAME',
                'BOOKS VIEWED',
                'CHAPTERS VIEWED',
                'LAST ACTIVITY DATE'
            );
            $htmlString = $this->xxxxtimePerClientReport($persial_survey, $ara);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'sumusers' . 'write.xls');
            echo json_encode($json_return);
        }
    }
    public function reportby_booksid()
    {
        $bookid = $this->input->post('bookid');
        $courseid = $this->input->post('courseid');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $bookname = $this->input->post('booktext');
        // $courseid,$bookid,$startdate,$enddate
        $persial_survey =  $this->books_data($courseid, $bookid, $startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For Views By Books",
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            //Modified Data
            $table_data['survey_reportdata'] = $persial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = "View By Book " . $bookname;
            $table_data['table_survey_url'] = 'pages/table/viewbybook_table';
            $json_return = array(
                'report' => "View By Book " . $bookname . "Report in Range" . $startdate . '  To ' . $enddate,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/viewbybook_temp', $table_data, true),
                'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'viewbybook' . 'write.xls'
            );
            $arrange_xml = array();
            foreach ($persial_survey as $keya => $valuea) {
                unset_post($valuea, 'date_inserted');
                $nowarray = array($valuea['name_course'], $valuea['book_name'], $valuea['user_id'], $valuea['he_names'], $valuea['datelike'], $valuea['hoursmins']);
                array_push($arrange_xml, $nowarray);
            }
            $ara = array(
                'COURSE',
                'BOOK',
                'USERNAME',
                'FULL NAME',
                'DATE',
                'TIME'
            );
            $htmlString = $this->xxxxtimePerClientReport($arrange_xml, $ara);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'viewbybook' . 'write.xls');
            echo json_encode($json_return);
        }
    }
    public function reportby_viewchapter()
    {
        $bookid = $this->input->post('bookid');
        $courseid = $this->input->post('courseid');
        $chapterid = $this->input->post('chapterid');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $chaptername = $this->input->post('chaptertext');
        //  public function perchapter_data($courseid, $bookid,$chapterid, $startdate, $enddate)
        $persial_survey =  $this->perchapter_data($courseid, $bookid, $chapterid, $startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For Views By Chapter " . $chaptername,
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            //Modified Data
            $table_data['survey_reportdata'] = $persial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = "View By Chapter " . $chaptername;
            $table_data['table_survey_url'] = 'pages/table/viewbychapter_table';
            $json_return = array(
                'report' => "View By Chapter " . $chaptername . "Report in Range" . $startdate . '  To ' . $enddate,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/viewbychapter_temp', $table_data, true),
                'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'viewbychapter' . 'write.xls'
            );
            $arrange_xml = array();
            foreach ($persial_survey as $keya => $valuea) {
                unset_post($valuea, 'date_inserted');
                $nowarray = array($valuea['name_course'], $valuea['book_name'], $valuea['chaptername'], $valuea['user_id'], $valuea['he_names'], $valuea['datelike'], $valuea['hoursmins']);
                array_push($arrange_xml, $nowarray);
            }
            $ara = array(
                'COURSE',
                'BOOK',
                'CHAPTER',
                'USERNAME',
                'FULL NAME',
                'DATE',
                'TIME'
            );
            $htmlString = $this->xxxxtimePerClientReport($arrange_xml, $ara);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'viewbychapter' . 'write.xls');
            echo json_encode($json_return);
        }
    }
    #More Report Data Functions Below
    #Summery Book Report
    public function sum_book_data($startdate, $enddate)
    {
        // $startdate = "01-04-2021";
        // $enddate = "30-04-2021";
        $persial_survey = $this->universal_model->book_query_two_model(array('user_id', 'course_shortname', 'name_course', 'book_name', 'book_id', 'chaptername', 'date_inserted'), $startdate, $enddate);
        // print_array($persial_survey);
        $output = array_reduce($persial_survey, function (array $carry, array $item) {
            $city = $item['book_id'];
            if (array_key_exists($city, $carry)) {
                $carry[$city]['user_id'] .= '@' . $item['user_id'];
                $carry[$city]['course_shortname'] .= '@' . $item['course_shortname'];
                $carry[$city]['name_course'] .= '@' . $item['name_course'];
                $carry[$city]['book_name'] .= '@' . $item['book_name'];
                $carry[$city]['book_id'] .= '@' . $item['book_id'];
                $carry[$city]['chaptername'] .= '@' . $item['chaptername'];
                $carry[$city]['date_inserted'] .= '@' . $item['date_inserted'];
            } else {
                $carry[$city] = $item;
            }
            return $carry;
        }, array());
        $output_values = array_values($output);
        $book_course_check = array();
        $new_array_mama = array();
        foreach ($output_values as $key_peng => $value_peng) {
            $book_id_arr = explode("@", $value_peng['book_id']);
            $book_name_arr = explode("@", $value_peng['book_name']);
            $course_shortname_arr = explode("@", $value_peng['course_shortname']);
            #arrays above
            $booknamenon = str_replace(' ', '', $book_name_arr[0]);
            $unique_bookchap_id = $booknamenon . '' . $course_shortname_arr[0];
            if (array_key_exists($unique_bookchap_id, $book_course_check)) {
                #Handling the anomaly
                if ($book_course_check[$unique_bookchap_id] != $book_id_arr[0]) {
                    #Value One
                    $valueone = $new_array_mama[$unique_bookchap_id];
                    #Value Two
                    $valueone['user_id'] = $valueone['user_id'] . '@' . $value_peng['user_id'];
                    $valueone['course_shortname'] = $valueone['course_shortname'] . '@' . $value_peng['course_shortname'];
                    $valueone['name_course'] = $valueone['name_course'] . '@' . $value_peng['name_course'];
                    $valueone['book_name'] = $valueone['book_name'] . '@' . $value_peng['book_name'];
                    $valueone['book_id'] = $valueone['book_id'] . '@' . $value_peng['book_id'];
                    $valueone['chaptername'] = $valueone['chaptername'] . '@' . $value_peng['chaptername'];
                    $valueone['date_inserted'] = $valueone['date_inserted'] . '@' . $value_peng['date_inserted'];
                    $new_array_mama[$unique_bookchap_id] = $valueone;
                }
            } else {
                $book_course_check[$unique_bookchap_id] = $book_id_arr[0];
                $new_array_mama[$unique_bookchap_id] = $value_peng;
            }
        }
        $array_mega = array();
        foreach ($new_array_mama as $keyn => $valuen) {
            $user_id_array = explode("@", $valuen['user_id']);
            // $course_shortname_array = explode("@", $valuen['course_shortname']);
            $name_course_array = explode("@", $valuen['name_course']);
            $book_name_array = explode("@", $valuen['book_name']);
            // $book_id_array = explode("@", $valuen['book_id']);
            $chaptername_array = explode("@", $valuen['chaptername']);
            $array_jeje = array();
            $chapter_count = 0;
            foreach ($user_id_array as $keyp => $valuep) {
                $user_id_chaptername = $valuep . '' . str_replace(' ', '', $chaptername_array[$keyp]);
                if (!in_array($user_id_chaptername, $array_jeje)) {
                    array_push($array_jeje, $user_id_chaptername);
                    $chapter_count += 1;
                }
            }
            $user_id_array_unqui = array_unique($user_id_array);
            $sooth_array = array(
                'book' => $book_name_array[0],
                'course' => $name_course_array[0],
                'books_veiwed' => count($book_name_array),
                'chapters' => $chapter_count,
                'unique_users' => count($user_id_array_unqui),
            );
            array_push($array_mega, $sooth_array);
        }
        return $array_mega;
    }
    #Summery User Report
    public function sum_user_data($startdate, $enddate)
    {
        $persial_survey = $this->universal_model->book_query_two_model(array('user_id', 'he_names', 'book_name', 'book_id', 'chaptername', 'date_inserted'), $startdate, $enddate);
        // print_array($persial_survey);
        $output = array_reduce($persial_survey, function (array $carry, array $item) {
            $city = $item['user_id'];
            if (array_key_exists($city, $carry)) {
                $carry[$city]['user_id'] .= '@' . $item['user_id'];
                $carry[$city]['he_names'] .= '@' . $item['he_names'];
                $carry[$city]['book_name'] .= '@' . $item['book_name'];
                $carry[$city]['book_id'] .= '@' . $item['book_id'];
                $carry[$city]['chaptername'] .= '@' . $item['chaptername'];
                $carry[$city]['date_inserted'] .= '@' . $item['date_inserted'];
            } else {
                $carry[$city] = $item;
            }
            return $carry;
        }, array());
        $output_values = array_values($output);
        $array_mega = array();
        foreach ($output_values as $keyn => $valuen) {
            $user_id_array = explode("@", $valuen['user_id']);
            $he_names_array = explode("@", $valuen['he_names']);
            $book_name_array = explode("@", $valuen['book_name']);
            $book_id_array = explode("@", $valuen['book_id']);
            $chaptername_array = explode("@", $valuen['chaptername']);
            $date_inserted_array = explode("@", $valuen['date_inserted']);
            #End of arrays
            #Additional Function
            $last_act_array = $this->universal_model->get_value_max($user_id_array[0]);
            $last_act_array = array_shift($last_act_array);
            #End Function
            $book_name_unq = array_unique($book_name_array);
            $sooth_array = array(
                'fullnames' => $he_names_array[0],
                'username' => $user_id_array[0],
                'books_veiwed' => count($book_name_unq),
                'chapters' => count($chaptername_array),
                'lastactivitydate' => date('d-m-Y', strtotime($last_act_array['date_inserted']))
            );
            // get_value_max
            array_push($array_mega, $sooth_array);
        }
        return $array_mega;
        // print_array($array_mega);
    }
    public function getbooksin_course()
    {
        $course_id = $this->input->post('courseid');
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $functionname = 'mod_book_get_books_by_courses';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $this->get_admin_token()['token'],
            'wsfunction' => $functionname,
            'courseids[0]' => $course_id,
            'moodlewsrestformat' => 'json'

        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $plain_data = json_decode($server_output, true);
        $final_books = array();
        if (!empty($plain_data)) {
            $plain_data_1 = $plain_data['books'];
            foreach ($plain_data_1 as $key => $value) {
                $array_per_book = array('book_id' => $value['id'], 'bookname' => $value['name']);
                array_push($final_books, $array_per_book);
            }
        }
        echo json_encode($final_books);
        // cleanContent
        //    echo json_encode()
    }
    public function books_data($courseid, $bookid, $startdate, $enddate)
    {
        // $bookid = "94";
        // $startdate = "01-04-2021";
        // $enddate = "30-04-2021";
        $persial_survey = $this->universal_model->books_reports_chapter(array('name_course', 'book_name', 'user_id', 'he_names', 'date_inserted'), $startdate, $enddate, $courseid, $bookid, "book");
        return $persial_survey;
    }
    public function perchapter_data($courseid, $bookid, $chapterid, $startdate, $enddate)
    {
        // $bookid = "94";
        // $startdate = "01-04-2021";
        // $enddate = "30-04-2021";
        $persial_survey = $this->universal_model->books_reports_chapterson(array('name_course', 'book_name', 'user_id', 'he_names', 'date_inserted'), $startdate, $enddate, $courseid, $bookid, "book", $chapterid);
        return $persial_survey;
    }
    #End of End
    public function get_admin_token()
    {
        $domainname = 'https://app.healthyentrepreneurs.nl/login/token.php?username=mega&password=GoatNa123!@%23XCMan&service=addusers';
        $serverurl = $domainname . '/login/token.php?';
        $data = array();
        $server_output = curl_request($domainname, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        // print_array($array_of_output);
        return $array_of_output;
    }
}
