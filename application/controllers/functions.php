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
    
    public function create_content_nnn()
    {
        //Start ID
        $idcohort = $this->input->post('cohort_object');
        $domainname = 'https://app.healthyentrepreneurs.nl';
        $token = 'f84bf33b56e86a4664284d8a3dfb5280';
        $functionname = 'core_cohort_get_cohort_members';
        $serverurl = $domainname . '/webservice/rest/server.php';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'cohortids[0]' => $idcohort

        );
        $server_output = curl_request($serverurl, $data, "get", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        $shiftdata = array_shift($array_of_output);
        $checkem = $shiftdata['userids'];
        if (empty($checkem)) {
            $user_id = 0;
        } else {
            $numberusers = count($checkem);
            if ($numberusers <= 1) {
                $user_id = $checkem[0];
            } else {
                $user_id = $checkem[1];
            }
        }
        //End   ID
        // public function selectz($array_table_n, $table_n, $variable_1, $value_1)
        $vara = $this->universal_model->selectz('*', 'mdl_user', 'id', $user_id);
        if (empty($vara)) {
            echo empty_response("This User Does Not Exit/Shoud Login Once", 200);
            return null;
        }
        $user_creds = array_shift($vara);
        // $nameone =$user_id . '_attendencelog_' . "data.txt";
        // file_put_contents(APPPATH . '/datamine/' . $nameone, '<?php return ' . var_export($vara, true) . ';');
        #Login
        $domainname = base_url();
        $serverurl = $domainname . '/moodle/login';
        $data = array(
            'username' => $user_creds['username'],
            'password' => '123456',

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
    }