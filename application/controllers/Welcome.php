<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('universal_model');
		$this->load->model('user_model', '', TRUE);
	}

	public function index_temp()
	{
		$data['header'] = 'parts/header';
		$data['nav'] = 'parts/nav_home';
		$data['footer'] = 'parts/footer';
		$data['title'] = ucfirst("HE Enters");
		$this->load->view('home', $data);
	}
	public function index()
	{
		// redirect();
		// echo "<h1>Api Dashboard Comming .....</h1>";
		$this->landing(0);
	}
	public function landing($id = 0)
	{
		if ($this->session->userdata('logged_in_lodda')) {
			redirect(base_url('welcome/admin'));
		} else {
			$data['header'] = 'parts/header';
			switch ($id) {
				case 0:
					$data['content'] = 'pages/index';
					$this->load->view('pages/homeone', $data);
					break;
				case 1:
					$data['content'] = 'pages/register';
					$this->load->view('pages/homeone', $data);
					break;
				case 2:
					$data['content'] = 'pages/login';
					$this->load->view('pages/homeone', $data);
					break;
				default:
					redirect(base_url());
					break;
			}
		}
	}
	public function admin($var = 0, $id = null, $id_two = null)
	{
		// print_array($this->session->userdata('logged_in_lodda'));
		// njovu
		if ($this->session->userdata('logged_in_lodda')) {
			$data['header'] = 'parts/header';
			$data['sidenav'] = 'pages/admin/navadmin';
			$data['user_profile'] = array();
			$data['survey_name'] = array();
			$server_output = curl_request(base_url('getcourses'), array(), "get", array('App-Key: 123456'));
			$courses = json_decode($server_output, true);
			if (empty($courses)) {
				$courses = array();
			}
			$data['courses'] = $courses;
			// print_array($server_output);
			switch ($var) {
				case 0:
					$data['content_admin'] = 'pages/admin/admin_content';
					$this->load->view('pages/hometwo', $data);
					break;
				case 1:
					$data['icon_image'] = 'https://picsum.photos/200/300';
					$data['content_admin'] = 'pages/admin/admin_quiz';
					$this->load->view('pages/hometwo', $data);
					break;
				case 2:
					// public function selectz($array_table_n, $table_n, $variable_1, $value_1)
					$attempt_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
					$data['content_admin'] = 'pages/admin/surveylist';
					$data['surveydatas'] = $attempt_n_n;
					$this->load->view('pages/hometwo', $data);
					// $this->load->view('pages/homequiz', $data);
					break;
				case 3:
					$id = $this->input->get('id');
					$attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
					// print_array($attempt_n_n_one);
					$data['content_admin'] = 'pages/admin/surveyinstance';
					$data['surveydataone'] = array_shift($attempt_n_n_one);
					$data['id'] = $id;
					$this->load->view('pages/hometwo', $data);
					break;
				case 4:
					$data['cohorts'] = $this->getme_chort_details();
					$data['surveys'] = $this->get_surveys();
					$data['survey_cohort'] = $this->universal_model->join_suv_cohot();
					$data['content_admin'] = 'pages/admin/cohorts';
					$this->load->view('pages/hometwo', $data);
					break;
				case 5:
					$id = $this->input->get('id');
					$attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
					$data['surveydataone'] = array_shift($attempt_n_n_one);
					$data['content_admin'] = 'pages/admin/imgsurveyinstance';
					$data['id'] = $id;
					$this->load->view('pages/hometwo', $data);
					break;
				case 6:
					$id = $this->input->get('id');
					$attempt_n_n_one = $this->universal_model->join_suv_report($id);
					$data['survey_reportdata'] = $attempt_n_n_one;
					$data['controller'] = $this;
					$data['content_admin'] = 'pages/admin/survey_report';
					$data['id'] = $id;
					$this->load->view('pages/hometwo', $data);
					break;
				case 7:
					$specific_array = $this->detailsurvey($id, $id_two);
					$data['survey_instance'] = $specific_array;
					$data['controller'] = $this;
					$userid = $this->input->get('userid');
					$name = $this->input->get('name');
					$user_profile = $this->get_meuserdetails($userid);
					$user_profile = array_shift($user_profile);
					$data['user_profile'] = $user_profile;
					$data['survey_name'] = $name;
					$data['content_admin'] = 'pages/admin/survey_instance';
					$this->load->view('pages/hometwo', $data);
					// print_array($specific_array);
					break;
				case 8:
					$attempt_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
					$data['surveydatas'] = $attempt_n_n;
					$data['content_admin'] = 'report/survey_reportindex';
					$this->load->view('pages/hometwo', $data);
					break;
				default:
					break;
			}
		} else {
			$data['content'] = 'pages/index';
			$this->load->view('pages/homeone', $data);
		}
	}

	public function getme_chort_details()
	{
		$domainname = 'https://app.healthyentrepreneurs.nl';
		$token = 'f84bf33b56e86a4664284d8a3dfb5280';
		$functionname = 'core_cohort_get_cohorts';
		$serverurl = $domainname . '/webservice/rest/server.php';
		$data = array(
			'wstoken' => $token,
			'wsfunction' => $functionname,
			'moodlewsrestformat' => 'json',
			'cohortids' => array(),

		);
		$server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
		$array_of_output = json_decode($server_output, true);
		return $array_of_output;
		// print_array($array_of_output);
	}
	public function get_surveys()
	{
		$attempt_d_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
		$array_object = array();
		foreach ($attempt_d_n_n as $key => $value) {
			$custome_onw = array(
				'id' => $value['id'],
				'fullname' => $value['name'],
				'categoryid' => 2,
				'source' => $value['type'],
				'summary_custome' => $value['surveydesc'],
				"next_link" => base_url('survey/getnexlink/') . $value['id'],
				'image_url_small' => base_url('uploadscustome/') . $value['image'],
				'image_url' => base_url('uploadscustome/') . $value['image_url_small']
			);
			array_push($array_object, $custome_onw);
		}
		return $array_object;
	}

	#Test Get User Details
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
	public function detailsurvey_n($id, $idsurv)
	{
		$report_data = $this->universal_model->join_suv_report_details($idsurv, $id);
		$report_data_n = array_shift($report_data);
		return $report_data_n;
	}
	public function detailsurvey($id, $idsurv)
	{
		// $id = $this->input->get('id');
		// $idsurv = $this->input->get('idsurv');
		$report_data = $this->universal_model->join_suv_report_details($idsurv, $id);
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
		// print_array($array_table_values);
	}
}
