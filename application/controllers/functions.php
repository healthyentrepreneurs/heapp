 public function report_surveydetails_n()
    {
        $_POST['selectclientid'] = 10;
        $_POST['selectclientname'] = "Workflow: ICCM children under 5 (KE)";
        $_POST['startdate'] = "01-02-2021";
        $_POST['enddate'] = "28-02-2021";
        $surveyid = $this->input->post('selectclientid');
        $selectclientname = $this->input->post('selectclientname');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->join_suv_report($surveyid, $startdate, $enddate);
        $modipersial_survey = array();
        $bigest_array = array();
        $int_key = 0;
        $key_then = 0;
        foreach ($persial_survey as $key => $value_datauser) {
            // $call_details_url = base_url('user/get_meuserdetails/' . $value_datauser['userid']);
            $user_details_output = $this->get_meuserdetails($value_datauser['userid']);
            $jaja_raary = array_shift($user_details_output);
            $detail_surveyinsta = $this->detailsurvey($value_datauser['id'], $value_datauser['surveyid']);
            $biggest = count($detail_surveyinsta);
            if ($biggest >= $int_key) {
                $int_key = $biggest;
                $key_then = $key;
            }
            $user_details = array(
                // 'survey_name' => $value_datauser['name'],
                'username' => $jaja_raary['username'],
                'fullname' => $jaja_raary['fullname'],
                'submitted_date' => $value_datauser['dateaddedsurvey'],
                'data_submission' => $detail_surveyinsta
            );
            // print_array($detail_surveyinsta);
            $bigest_array['key'] = $key_then;
            $bigest_array['howbig'] = $int_key;
            array_push($modipersial_survey, $user_details);
            // print_array($user_details);
        }
        $table_data['key_bign'] = $bigest_array;
        $table_data['survey_reportdata'] = $modipersial_survey;
        $table_data['startdate'] = $startdate;
        $table_data['enddate'] = $enddate;
        $table_data['controller'] = $this;
        $table_data['taskname'] = $selectclientname;
        // The Flesh
        $table_data['table_survey_url'] = 'pages/table/survey_tabledetails';
        $json_return = array(
            'report' => "Report For Survey  in Range" . $selectclientname,
            'status' => 1,
            'data' => $this->load->view('pages/cohort/survey_reportshowtemp', $table_data, true),
            'path' => FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'detailswrite.xls'
        );
        // echo json_encode($json_return);
        $arrayexcel = array();
        foreach ($modipersial_survey as $key => $value_excel) {
            $time_now = explode(" ", $value_excel['submitted_date']);
            $username = $value_excel['username'];
            $fullname = $value_excel['fullname'];
            $datesub = $time_now[0];
            $timesub = $time_now[1];
            $array_now = $value_excel['data_submission'];
            $count_em = count($array_now);
            $array_jaja = array(
                'username' => $username,
                'fullname' => $fullname,
                'datesub' => $datesub,
                'timesub' => $timesub,
            );
            foreach ($array_now as $key => $getmevalue) {
                if (array_key_exists('value_score', $getmevalue)) {
                    $value_score = $getmevalue['value_score'];
                } else {
                    $value_score = "";
                }
                $key_name = $key . "value_score";
                $array_jaja[$key_name] = $value_score;
                //     $array_jaja = array(
                //         'username' => $username,
                //         'fullname' => $fullname,
                //         'datesub' => $datesub,
                //         'timesub' => $timesub,
                //         "data" => $value_score
                //     );
            }
            $keyone = 1;
            if ($int_key > $count_em) {
                $additional_td = $int_key - $count_em;
                for ($i = 0; $i < $additional_td; $i++) {
                    $n = $keyone + $i;
                    $array_jaja[$n] = "";
                }
            }
            array_push($arrayexcel, $array_jaja);
        }
        $getm_titles = $modipersial_survey[$key_then]['data_submission'];
        $titles = array_column($getm_titles, 'title');
        $more_names = array(
            'Username',
            'Full Names',
            'Date Submited',
            'Time Submited'
        );
        $final = array_merge($more_names, $titles);
        // print_array($final);
        //Finishing Touch
        $htmlString = $this->xxxxtimePerClientReport($arrayexcel, $final);
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save(FCPATH . 'excelfiles/' . $this->session->userdata('logged_in_lodda')['id'] . 'detailswrite.xls');
        echo json_encode($json_return);
        // print_array($modipersial_survey);
    }